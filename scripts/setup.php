<?php

// Define available database options
echo "Select the database type to use:\n";
echo "1. MySQLi\n";
echo "2. SQLite\n";
echo "3. PostgreSQL\n";
echo "Enter the number of your choice (1-3): ";

$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

// Set default database configuration path
$configFile = __DIR__ . '/../config/database.php';

// Initialize database config array
$dbConfig = [];

switch ($choice) {
    case 1: // MySQLi
        echo "Enter MySQL database name: ";
        $dbname = trim(fgets($handle));

        echo "Enter MySQL username: ";
        $username = trim(fgets($handle));

        echo "Enter MySQL password: ";
        $password = trim(fgets($handle));

        echo "Enter MySQL host (default: localhost): ";
        $host = trim(fgets($handle)) ?: 'localhost';

        $dbConfig = [
            "driver" => "mysql",
            "host" => $host,
            "dbname" => $dbname,
            "username" => $username,
            "password" => $password,
            "charset" => "utf8mb4"
        ];
        break;
    case 2: // SQLite
        echo "Enter SQLite database path (e.g., ./database.sqlite): ";
        $path = trim(fgets($handle));

        $dbConfig = [
            "driver" => "sqlite",
            "path" => $path
        ];
        break;
    case 3: // PostgreSQL
        echo "Enter PostgreSQL database name: ";
        $dbname = trim(fgets($handle));

        echo "Enter PostgreSQL username: ";
        $username = trim(fgets($handle));

        echo "Enter PostgreSQL password: ";
        $password = trim(fgets($handle));

        echo "Enter PostgreSQL host (default: localhost): ";
        $host = trim(fgets($handle)) ?: 'localhost';

        $dbConfig = [
            "driver" => "pgsql",
            "host" => $host,
            "dbname" => $dbname,
            "username" => $username,
            "password" => $password
        ];
        break;
    default:
        echo "Invalid choice. Defaulting to MySQLi.\n";
        $dbConfig = [
            "driver" => "mysql",
            "host" => "localhost",
            "dbname" => "restaurant",
            "username" => "root",
            "password" => "",
            "charset" => "utf8mb4"
        ];
        break;
}

// Write configuration to file
file_put_contents($configFile, "<?php\nreturn " . var_export($dbConfig, true) . ";\n");

echo "Database configuration saved to '$configFile'.\n";

// Create the users table in the selected database
echo "Creating users table...\n";
$pdo = new PDO($dbConfig['driver'] . ':host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['username'], $dbConfig['password']);

$tableCreationQuery = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$pdo->exec($tableCreationQuery);

echo "✔️  Users table created successfully.\n";
