<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Category;
use App\Models\Event;

$admin = User::create(['name' => 'Admin', 'email' => 'admin@admin.com', 'password' => bcrypt('password'), 'role' => 'superadmin']);
$hima = User::create(['name' => 'HIMA', 'email' => 'hima@admin.com', 'password' => bcrypt('password'), 'role' => 'user']);

$cat = Category::create(['name' => 'Tech', 'slug' => 'tech', 'description' => 'tech desc']);

Event::create([
    'user_id' => $hima->id,
    'category_id' => $cat->id,
    'title' => 'Free Event',
    'description' => 'A free tech event.',
    'date' => now()->addDays(2),
    'location' => 'Jogja',
    'price' => 0,
    'stock' => 100
]);

Event::create([
    'user_id' => $hima->id,
    'category_id' => $cat->id,
    'title' => 'Past Event',
    'description' => 'A past tech event.',
    'date' => now()->subDays(2),
    'location' => 'Jogja',
    'price' => 50000,
    'stock' => 100
]);

echo "Seeded successfully!";
