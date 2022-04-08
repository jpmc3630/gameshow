<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $file = database_path('seeders/questions1.csv');

        $questions = Reader::createFromPath($file)
                    ->setDelimiter(',')
                    ->setHeaderOffset(0);

        ProgressBar::setFormatDefinition('custom', '<info>%current% / %max%</info> <fg=yellow;bg=black>%message%</>');
        $this->bar = $this->command->getOutput()->createProgressBar($questions->count());
        $this->bar->setFormat('custom');
        $this->bar->setMessage('Saving questionss.');
        $this->bar->start();

        foreach ($questions as $question) {
            // if ($question['deleted_at'] == 'NULL') continue;

            $questionModel = Question::updateOrCreate([
              'question' => $question['question'],
            ], [
                'category'     => $question['category'],
                'answer' => $question['answer'],
                'first_incorrect' => $question['first_incorrect'],
                'second_incorrect' => $question['second_incorrect'],
            ]);
            
            $this->bar->setMessage('Saving questions: '.$questionModel->question);
            $this->bar->advance();
        }
      $this->command->getOutput()->newLine();
    }
}
