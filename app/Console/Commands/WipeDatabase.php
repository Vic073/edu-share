<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Console\Command;

class WipeDatabase extends Command
{
    
    protected $signature = 'db.wipe-data';

    
    protected $description = 'delete all data from the tables but keep the tables';

    
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $key = "Tables_in_$dbName";

        foreach($tables as $table) {
            $tableName = $table->$key;
            DB::table($tableName)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('All table data wiped.');
        
    }
}
