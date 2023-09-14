<?php

namespace App\Database\Seeds;

use App\Models\Comment;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CommentSeeder extends Seeder
{
	public function run()
	{
		$comment = new Comment();
		$faker = \Faker\Factory::create();

		for($i=0; $i < 10; $i++) {
			$comment->save(
				[
					'name' => $faker->name,
					'email' => $faker->email,
					'message' => $faker->text(200),
					'date' => Time::createFromTimestamp($faker->unixTime)->format('Y-m-d'),
				]
			);
		}

	}
}
