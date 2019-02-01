<?php

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
        // $this->call(UsersTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(AnswersTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(QuestionTagTableSeeder::class);
        if(DB::connection()->getName() == 'pgsql')
        {
            $tablesToCheck = array('questions', 'answers', 'tags');
            foreach($tablesToCheck as $tableToCheck)
            {
                $this->command->info('Checking the next id sequence for '.$tableToCheck);
                $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
                $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();
                if($nextId->nextval < $highestId->max)
                {
                    DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
                    $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
                    $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();
                    if($nextId->nextval > $highestId->max)
                    {
                        $this->command->info($tableToCheck.' autoincrement corrected');
                    }
                    else
                    {
                        $this->command->info('Arff! The nextval sequence is still all screwed up on '.$tableToCheck);
                    }
                }
            }
        }
    }
}
