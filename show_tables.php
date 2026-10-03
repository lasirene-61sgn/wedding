<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['guest_lists', 'guest_family_members'];
foreach($tables as $t) {
    try {
        $res = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE $t");
        echo $res[0]->{'Create Table'} . "\n\n";
    } catch (\Exception $e) {
        echo $e->getMessage() . "\n";
    }
}
