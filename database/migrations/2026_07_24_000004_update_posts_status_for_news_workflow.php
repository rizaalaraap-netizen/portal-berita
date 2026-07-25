<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE posts MODIFY status VARCHAR(20) NOT NULL DEFAULT 'draft'");
        }

        DB::table('posts')
            ->where('status', 'publish')
            ->update(['status' => 'published']);
    }

    public function down(): void
    {
        DB::table('posts')
            ->where('status', 'published')
            ->update(['status' => 'publish']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE posts MODIFY status ENUM('draft', 'publish') NOT NULL DEFAULT 'draft'");
        }
    }
};
