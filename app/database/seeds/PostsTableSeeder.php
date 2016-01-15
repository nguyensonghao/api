<?php 

class PostsTableSeeder extends Seeder {


    public function run()
    {
        // Remove any existing data
        // DB::table('pages')->truncate();

        // $faker = Faker\Factory::create();

        // Generate some dummy data
        for($i=0; $i<30; $i++) {
          Post::create([
            'title' => $i + '1',
            'content' => $i + '1',
            'tags' => $i + '1'
          ]);
        }
    }

}