<?php

require_once __DIR__ . '/config/database.php';

$pdo = Database::connect();
$migrationPath = __DIR__ . '/migrations';

$files = scandir($migrationPath);

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $filePath = "$migrationPath/$file";
        require_once $filePath;

        // Extract class name from the file content using token parsing
        $tokens = token_get_all(file_get_contents($filePath));
        $className = null;

        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i][0] === T_CLASS && isset($tokens[$i + 2][1])) {
                $className = $tokens[$i + 2][1];
                break;
            }
        }

        if ($className && class_exists($className)) {
            $migration = new $className();
            echo "Running migration: $className\n";
            $migration->up($pdo);
            echo "✔️  Done\n\n";
        } else {
            echo "❌ Class not found in $file\n";
        }
    }
}

