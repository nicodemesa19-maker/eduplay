<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'test@example.com')->first();
if ($user) {
    $user->password = Illuminate\Support\Facades\Hash::make('password');
    $user->save();
    echo "Password set to 'password' for test@example.com\n";
}
