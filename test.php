use App\Database;
use App\Field;
use App\Process;

$config = [
    'host' => 'localhost',
    'database' => 'test_db',
    'user' => 'root',
    'password' => 'root'
];

$db = new Database($config);

// Создание процесса и добавление полей
$process = new Process('Test Process');
$process->addField(new Field('Text', 'text', 'Sample Text'));
$process->addField(new Field('Number', 'number', 123, '%d'));
$process->addField(new Field('Date', 'date', '2024-06-11', 'Y-m-d'));

$process->save($db);
