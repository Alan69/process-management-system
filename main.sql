CREATE TABLE processes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    type ENUM('text', 'number', 'date') NOT NULL,
    default_value VARCHAR(255),
    format VARCHAR(255)
);

CREATE TABLE process_fields (
    process_id INT,
    field_id INT,
    value VARCHAR(255),
    PRIMARY KEY (process_id, field_id),
    FOREIGN KEY (process_id) REFERENCES processes(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES fields(id) ON DELETE CASCADE
);
