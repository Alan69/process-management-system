namespace App;

class Database {
    private \PDO $connection;

    public function __construct(array $config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['database']}";
        $this->connection = new \PDO($dsn, $config['user'], $config['password']);
    }

    public function getConnection(): \PDO {
        return $this->connection;
    }
}

interface FieldInterface {
    public function getName(): string;
    public function getType(): string;
    public function getDefaultValue(): mixed;
    public function getFormat(): ?string;
}

class Field implements FieldInterface {
    private string $name;
    private string $type;
    private mixed $defaultValue;
    private ?string $format;

    public function __construct(string $name, string $type, mixed $defaultValue = null, ?string $format = null) {
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        $this->format = $format;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getDefaultValue(): mixed {
        return $this->defaultValue;
    }

    public function getFormat(): ?string {
        return $this->format;
    }
}

class Process {
    private int $id;
    private string $name;
    private array $fields = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function addField(Field $field): void {
        $this->fields[$field->getName()] = $field;
    }

    public function getFields(): array {
        return $this->fields;
    }

    public function save(Database $db): void {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("INSERT INTO processes (name) VALUES (:name)");
        $stmt->execute([':name' => $this->name]);
        $this->id = (int)$conn->lastInsertId();

        foreach ($this->fields as $field) {
            $stmt = $conn->prepare("INSERT INTO fields (name, type, default_value, format) VALUES (:name, :type, :default_value, :format)");
            $stmt->execute([
                ':name' => $field->getName(),
                ':type' => $field->getType(),
                ':default_value' => $field->getDefaultValue(),
                ':format' => $field->getFormat()
            ]);
            $fieldId = (int)$conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO process_fields (process_id, field_id, value) VALUES (:process_id, :field_id, :value)");
            $stmt->execute([
                ':process_id' => $this->id,
                ':field_id' => $fieldId,
                ':value' => $field->getDefaultValue()
            ]);
        }
    }
}
