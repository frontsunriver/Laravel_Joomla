<?php

use Illuminate\Database\Seeder;
use App\Models\Taxonomy;

class TaxonomyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taxonomy')->truncate();
        $tax = new Taxonomy();
        $data = [
            [
                'taxonomy_title' => 'Home Type',
                'taxonomy_name' => 'home-type',
                'post_type' => 'home',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Home Amenity',
                'taxonomy_name' => 'home-amenity',
                'post_type' => 'home',
                'created_at' => time()
            ],            
            [
                'taxonomy_title' => 'Categories',
                'taxonomy_name' => 'post-category',
                'post_type' => 'post',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Tags',
                'taxonomy_name' => 'post-tag',
                'post_type' => 'post',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Languages',
                'taxonomy_name' => 'experience-languages',
                'post_type' => 'experience',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Inclusions',
                'taxonomy_name' => 'experience-inclusions',
                'post_type' => 'experience',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Exclusions',
                'taxonomy_name' => 'experience-exclusions',
                'post_type' => 'experience',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Experience Types',
                'taxonomy_name' => 'experience-type',
                'post_type' => 'experience',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Car Types',
                'taxonomy_name' => 'car-type',
                'post_type' => 'car',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Car Equipments',
                'taxonomy_name' => 'car-equipment',
                'post_type' => 'car',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Car Features',
                'taxonomy_name' => 'car-feature',
                'post_type' => 'car',
                'created_at' => time()
            ],
            [
                'taxonomy_title' => 'Home Facilities Fields',
                'taxonomy_name' => 'home-facilities',
                'post_type' => 'home',
                'created_at' => time()
            ]
        ];

        foreach ($data as $args) {
            $tax->create($args);
        }
    }
}
