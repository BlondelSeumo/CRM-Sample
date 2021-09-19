<?php

trait TruncateAllTablesTrait
{
    public function truncateAllTables()
    {
        /*
         * This is to Truncate all tables
         * except migrations table
         */
        $tables = DB::select('SHOW TABLES');

        $tables_in_db = 'Tables_in_'.env('DB_DATABASE');

        foreach ($tables as $table) {
            if ($table->$tables_in_db !== 'migrations')
                DB::table($table->$tables_in_db)->truncate();

            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln($table->$tables_in_db . ' truncate successful');
        }
    }
}
