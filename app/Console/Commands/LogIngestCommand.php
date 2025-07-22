<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LogIngestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:ingest-csv {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports logs from a CSV file with headers: level, message, context, source';

    /**
     * Expected CSV headers.
     */
    protected array $expectedHeaders = ['level', 'message', 'context', 'source'];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->displayCsvFormatHelp();

        $logPath = public_path($this->argument('filepath'));
        if (! file_exists($logPath)) {
            $this->error('No file Exists');

            return self::FAILURE;
        }
        $handle = fopen($logPath, 'r');
        if (! $handle) {
            $this->error("Could not open file: $logPath");

            return self::FAILURE;
        }
        $this->info("Importing logs from: $logPath");

        $headerSkipped = false;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (! $headerSkipped) {
                if (array_map('strtolower', $row) != $this->expectedHeaders) {
                    $this->error('Provided CSV header is invalid or does not match expected format.');
                    fclose($handle);

                    return self::FAILURE;
                }
                $headerSkipped = true;

                continue;
            }
            try {
                $this->insertLogRow($row);
                $this->info('Inserting files '.$row[0]);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
        fclose($handle);
        $this->info('Import Successfully');

        return self::SUCCESS;
    }

    /**
     * Displays help on CSV structure.
     */
    protected function displayCsvFormatHelp(): void
    {
        $this->info('Expected CSV Format:');
        $this->line('Headers: level,message,context,source');
        $this->line('Example row: error,Something broke,context string,api-gateway');
        $this->newLine();
    }

    /**
     * Inserts a single log row into the database.
     */
    protected function insertLogRow(array $row): void
    {
        DB::table('logs')->insert([
            'level' => $row[0] ?? '',
            'message' => $row[1] ?? '',
            'context' => $row[2] ?? '',
            'source' => $row[3] ?? '',
        ]);
    }
}
