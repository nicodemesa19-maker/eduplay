<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // PostgreSQL requires explicit USING clause for type conversion
        DB::connection('supabase')->statement("
            ALTER TABLE users 
            ALTER COLUMN display_badge TYPE json 
            USING CASE 
                WHEN display_badge IS NULL OR display_badge = '' THEN NULL
                ELSE json_build_array(display_badge)
            END
        ");
    }

    public function down()
    {
        // Convert back to varchar, taking first element of JSON array
        DB::connection('supabase')->statement("
            ALTER TABLE users 
            ALTER COLUMN display_badge TYPE varchar(100) 
            USING CASE 
                WHEN display_badge IS NULL THEN NULL
                WHEN jsonb_array_length(display_badge::jsonb) > 0 THEN display_badge->>0
                ELSE NULL
            END
        ");
    }
};
