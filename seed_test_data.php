<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Seed database
$app->make(\Illuminate\Database\Seeder::class)->call(\Database\Seeders\DatabaseSeeder::class);

echo "Database seeded successfully!\n";
