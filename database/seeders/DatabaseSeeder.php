<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AffiliateLink;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\File;
use App\Models\Marketplace;
use App\Models\Spec;
use App\Models\SpecCategory;
use App\Models\Type;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Blog::factory(100)
            // ->has(File::factory()->count(1), 'thumbnail')
            ->create();

        Brand::factory(10)
            // ->has(File::factory()->count(1), 'thumbnail')
            ->create();

        Type::insert([
            ['name' => 'mobil', 'slug' => 'mobil', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sepeda motor', 'slug' => 'sepeda-motor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sepeda', 'slug' => 'sepeda', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'lainnya', 'slug' => 'lainnya', 'created_at' => now(), 'updated_at' => now()]
        ]);

        $marketplace = Marketplace::factory()->create();

        $typeIds = Type::all()->pluck('id')->toArray();
        Brand::all()->each(function ($brand) use ($typeIds, $marketplace) {
            foreach ($typeIds as $t) {
                Vehicle::factory(5)
                    // ->has(File::factory()->count(3), 'pictures')
                    ->has(AffiliateLink::factory()->count(3)->for($marketplace))
                    ->create([
                        'brand_id' => $brand->id,
                        'type_id' => $t
                    ]);
            }
        });

        SpecCategory::factory(4)
                    ->has(Spec::factory()->count(4))
                    ->create();

        $specIds = Spec::all()->pluck('id')->toArray();
        Vehicle::all()->each(function ($vehicle) use ($specIds) {
            $faker = Faker::create();
            $vehicle->specs()->attach($specIds, ['value' => $faker->sentence()]);
        });
    }
}
