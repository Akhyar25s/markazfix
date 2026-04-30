<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Public Path: " . public_path() . PHP_EOL;
echo "Base Path: " . base_path() . PHP_EOL;

try {
    $vite = app(Illuminate\Foundation\Vite::class);
    // Use reflection to check the manifest path if possible, or just call a method that triggers lookup
    // In recent Laravel, Vite uses public_path('build/manifest.json') by default
    $manifestPath = public_path('build/manifest.json');
    echo "Expected Manifest Path: " . $manifestPath . PHP_EOL;
    echo "Manifest Exists: " . (file_exists($manifestPath) ? 'YES' : 'NO') . PHP_EOL;
    
    // Test the directive logic
    echo "Vite tags: " . $vite(['resources/css/app.css', 'resources/js/app.js']) . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}
