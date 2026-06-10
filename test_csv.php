<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\LaporanController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('role', 'pengurus_inti')->first();
Auth::login($user);

$controller = new LaporanController();
try {
    $response = $controller->exportCsv(1);
    ob_start();
    $response->sendContent();
    $content = ob_get_clean();
    file_put_contents('test_output.csv', $content);
    echo 'Saved CSV';
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
