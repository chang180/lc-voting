<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'test@test.com',
        ]);

        User::factory(19)->create();

        Category::factory()->create(['name' => 'Category 1']);
        Category::factory()->create(['name' => 'Category 2']);
        Category::factory()->create(['name' => 'Category 3']);
        Category::factory()->create(['name' => 'Category 4']);

        Status::factory()->create(['name' => 'Open']);
        Status::factory()->create(['name' => 'Considering']);
        Status::factory()->create(['name' => 'In Progress']);
        Status::factory()->create(['name' => 'Implemented']);
        Status::factory()->create(['name' => 'Closed']);


        Idea::factory(100)->existing()->create();

        // Generate unique votes, Ensure idea_id and user_id combinations are unique
        foreach (range(1, 20) as $user_id) {
            foreach (range(1, 100) as $idea_id) {
                if (rand(0,2)) {
                    Vote::factory()->create([
                        'user_id' => $user_id,
                        'idea_id' => $idea_id,
                    ]);
                }
            }
        }

        //generate comments for ideas
        foreach(Idea::all() as $idea) {
            Comment::factory(20)->existing()->create([
                'idea_id' => $idea->id,
            ]);
        }

    }
}
