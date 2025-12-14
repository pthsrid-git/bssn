<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetSequences extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:reset-sequences {table?}';

    /**
     * The console command description.
     */
    protected $description = 'Reset PostgreSQL sequences for all tables or specific table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');

        if ($table) {
            // Reset specific table
            $this->resetSequence($table);
        } else {
            // Get all tables with sequences
            $tables = $this->getAllTablesWithSequences();

            if (empty($tables)) {
                $this->warn('No tables with sequences found.');
                return;
            }

            $this->info('Found ' . count($tables) . ' tables with sequences');
            $this->newLine();

            foreach ($tables as $tableName) {
                $this->resetSequence($tableName);
            }
        }

        $this->newLine();
        $this->info('✓ Sequences reset completed!');
    }

    /**
     * Get all tables with sequences
     */
    protected function getAllTablesWithSequences()
    {
        $query = "
            SELECT
                t.table_name
            FROM
                information_schema.tables t
            JOIN
                information_schema.columns c ON t.table_name = c.table_name
            WHERE
                t.table_schema = 'public'
                AND t.table_type = 'BASE TABLE'
                AND c.column_default LIKE 'nextval%'
            GROUP BY
                t.table_name
            ORDER BY
                t.table_name
        ";

        $results = DB::select($query);

        return collect($results)->pluck('table_name')->toArray();
    }

    /**
     * Reset sequence for a table
     */
    protected function resetSequence($table)
    {
        try {
            // Get the primary key column name
            $primaryKey = $this->getPrimaryKeyColumn($table);

            if (!$primaryKey) {
                $this->warn("✗ No primary key found for {$table}");
                return;
            }

            // Get sequence name
            $sequenceName = $this->getSequenceName($table, $primaryKey);

            if (!$sequenceName) {
                $this->warn("✗ No sequence found for {$table}");
                return;
            }

            // Get max value
            $maxValue = DB::table($table)->max($primaryKey) ?? 0;
            $nextValue = $maxValue + 1;

            // Reset sequence
            DB::statement("ALTER SEQUENCE {$sequenceName} RESTART WITH {$nextValue}");

            $this->info("✓ {$table} → sequence reset to {$nextValue}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to reset {$table}: " . $e->getMessage());
        }
    }

    /**
     * Get primary key column name
     */
    protected function getPrimaryKeyColumn($table)
    {
        $query = "
            SELECT
                c.column_name
            FROM
                information_schema.table_constraints tc
            JOIN
                information_schema.constraint_column_usage AS ccu USING (constraint_schema, constraint_name)
            JOIN
                information_schema.columns AS c ON c.table_schema = tc.constraint_schema
                AND tc.table_name = c.table_name AND ccu.column_name = c.column_name
            WHERE
                constraint_type = 'PRIMARY KEY'
                AND tc.table_name = ?
            LIMIT 1
        ";

        $result = DB::select($query, [$table]);

        return $result[0]->column_name ?? null;
    }

    /**
     * Get sequence name for a table column
     */
    protected function getSequenceName($table, $column)
    {
        $query = "
            SELECT
                pg_get_serial_sequence(?, ?) as sequence_name
        ";

        $result = DB::select($query, [$table, $column]);

        return $result[0]->sequence_name ?? null;
    }
}
