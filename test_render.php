<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invite = App\Models\GuestList::first();
$hfamily = App\Models\HostFamilyDetails::first();
$ceremonies = App\Models\Ceramonies::all();

$view = view('guest.dashboard', [
    'invite' => $invite,
    'hfamily' => $hfamily,
    'detailedCeremonies' => $ceremonies,
    'status' => 'pending'
])->render();

file_put_contents('rendered.html', $view);
echo "Rendered to rendered.html\n";
