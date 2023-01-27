<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Pet;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRufus();
    }

    protected function createRufus(): void
    {
        $rufus = Pet::updateOrCreate([
            'id' => 288,
        ], [
            'name' => 'Rufus',
            'status' => 'available',
            'category_id' => Category::firstOrCreate([
                'name' => 'dogs',
            ])->id,
        ]);

        $rufus->tags()->attach([
            Tag::firstOrCreate([
                'name' => 'puppies',
            ])->id,
            Tag::firstOrCreate([
                'name' => 'hound',
            ])->id,
        ]);

        Photo::create([
            'url' => 'https://picsum.photos/id/237/200/300',
            'pet_id' => $rufus->id,
        ]);
    }
}
