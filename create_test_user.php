<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$user = new User();
$user->name = 'Test User';
$user->email = 'test@example.com';
$user->password = Hash::make('password');
$user->save();

echo 'User created successfully';
