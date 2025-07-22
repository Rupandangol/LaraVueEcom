<?php

namespace App\Console\Commands;

use App\Models\AllUser;
use App\Models\User;
use Illuminate\Console\Command;

class SimpleETLCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:Users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // confirm check for truncate or not
        // Get ALl Data from users
        // preprocess these data as the name has spaces make it first name and last name accordinly
        // [
        //     ['first_name'=>'asdf','last_name'=>'asdf'],
        //     ['first_name'=>'qwe','last_name'=>'qwe']
        // ]
        // Show the table with the preprocessed data before Inserting with the confirmation
        // insert Into AllUser Table with progress bar

        if ($this->confirm('Truncate AllUser Table?')) {
            AllUser::truncate();
        }
        $users = User::all();
        $NewData = [];

        foreach ($users as $key => $user) {
            $NewData[] = $this->separateName($user->name);
        }
        $collection = collect($NewData);
        $this->table([
            'first_name',
            'last_name',
        ], $collection);
        if (! $this->confirm('Are you sure?')) {
            $this->output->info('Cancelled');
            exit;
        }
        $this->output->progressStart(count($NewData));
        foreach ($collection->chunk(1000) as $item) {
            AllUser::insert($item->toArray());
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->newLine(1);

        $this->info('Completed');
    }

    private function separateName(string $name): array
    {
        $firstName = '';
        $lastName = '';
        $bName = explode(' ', $name);
        if (count($bName) <= 2) {
            $firstName = $bName[0];
            $lastName = $bName[1] ?? '';
        }
        if (count($bName) >= 3) {
            $firstName = $bName[0].' '.$bName[1];
            array_splice($bName, 0, 2);
            foreach ($bName as $item) {
                $lastName .= $item.' ';
            }
        }

        return ['first_name' => $firstName, 'last_name' => $lastName];
    }
}
