<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildFullTextSearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fts:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the FTS5 index for the notes table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Rebuilding notes_fts index...');

        try {
            // 1. Clear the FTS table
            DB::statement('DELETE FROM notes_fts');

            // 2. Repopulate from the main notes table
            DB::statement('
                INSERT INTO notes_fts(rowid, title, body_content)
                SELECT id, title, body_content FROM notes
            ');

            $this->info('FTS5 index rebuilt successfully!');
        } catch (\Throwable $e) {
            $this->error('Error rebuilding index: '.$e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
