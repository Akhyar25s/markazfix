<?php
require __DIR__ . '/vendor/autoload.php';
\ = require_once __DIR__ . '/bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

use App\Http\Controllers\LaporanController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

\ = User::where('role', 'pengurus_inti')->first();
Auth::login(\);

\ = new LaporanController();
try {
    \ = \->exportPdf(1);
    ob_start();
    \->sendContent();
    \ = ob_get_clean();
    file_put_contents('test_output.pdf', \);
    echo 'Saved PDF. First 10 chars: ' . substr(\, 0, 10);
} catch (\Exception \) {
    echo 'Error: ' . \->getMessage();
}

