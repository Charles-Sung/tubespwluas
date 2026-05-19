<?php

$dir = __DIR__ . '/database/migrations/';
$files = glob($dir . '2026*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    // Find the 'public function up(): void' and replace everything inside it.
    // Since we messed it up by inserting our own and leaving `});\n    }`, let's just clean it up.
    $content = preg_replace('/(public function up\(\): void\s*\{.*?\});\n\s*\});\n\s*\}/s', '$1' . "\n    }", $content);
    file_put_contents($file, $content);
}
echo "Fixed migrations.\n";
