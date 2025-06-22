<?php
// Seeder: StreamSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stream;

class StreamSeeder extends Seeder
{
    public function run(): void
    {
        Stream::factory()->count(4)->create();
    }
}
