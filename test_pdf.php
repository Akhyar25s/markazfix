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
    $response = $controller->exportPdf(1);
    echo 'PDF Success';
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
