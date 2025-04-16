<?php

$command = $argv[1] ?? null;
$name = $argv[2] ?? null;

if (!$command || !$name) {
    echo "Usage: php cli.php make:[type] [Name]\n";
    exit;
}

$types = ['controller', 'model', 'view', 'migration'];

if (!preg_match('/^make:(controller|model|view|migration)$/', $command, $matches)) {
    echo "Unknown command: $command\n";
    exit;
}

$type = $matches[1];

switch ($type) {
    case 'controller':
        $content = "<?php\n\nclass $name {\n\n    public function index() {\n        echo \"$name controller index method.\";\n    }\n\n}\n";
        $path = __DIR__ . "/app/controller/{$name}.php";
        break;

    case 'model':
        $content = "<?php\n\nclass $name {\n\n    // Define your model methods here\n\n}\n";
        $path = __DIR__ . "/app/models/{$name}.php";
        break;

    case 'view':
        $content = "<!-- $name View -->\n<h1>$name View</h1>\n";
        $path = __DIR__ . "/app/views/{$name}.php";
        break;

    case 'migration':
        $timestamp = date('Y_m_d_His');
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $filename = "{$timestamp}_{$name}.php";
        $path = __DIR__ . "/migrations/{$filename}";
        $content = "<?php\n\nclass {$className} {\n    public function up(\$pdo) {\n        // Create table query here\n    }\n\n    public function down(\$pdo) {\n        // Drop table query here\n    }\n}\n";
        break;
}

if (file_exists($path)) {
    echo "$type '$name' already exists.\n";
    exit;
}

file_put_contents($path, $content);
echo ucfirst($type) . " '$name' created successfully at $path\n";
