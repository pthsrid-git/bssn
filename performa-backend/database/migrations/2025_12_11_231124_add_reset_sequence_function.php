<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create function to reset all sequences
        DB::unprepared("
            CREATE OR REPLACE FUNCTION reset_sequence(table_name TEXT, column_name TEXT DEFAULT 'id')
            RETURNS VOID AS $$
            DECLARE
                seq_name TEXT;
                max_val INTEGER;
            BEGIN
                -- Get sequence name
                SELECT pg_get_serial_sequence(table_name, column_name) INTO seq_name;

                IF seq_name IS NOT NULL THEN
                    -- Get max value
                    EXECUTE format('SELECT COALESCE(MAX(%I), 0) + 1 FROM %I', column_name, table_name) INTO max_val;

                    -- Reset sequence
                    EXECUTE format('ALTER SEQUENCE %s RESTART WITH %s', seq_name, max_val);

                    RAISE NOTICE 'Sequence % reset to %', seq_name, max_val;
                END IF;
            END;
            $$ LANGUAGE plpgsql;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS reset_sequence(TEXT, TEXT)");
    }
};
