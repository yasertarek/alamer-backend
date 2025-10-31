<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop if already exist
        DB::statement("DROP VIEW IF EXISTS `admins_view`");
        DB::statement("DROP VIEW IF EXISTS `regular_users_view`");

        // Create admins view
        DB::statement("
            CREATE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admins_view` AS
            SELECT * FROM `users` WHERE `role` IN ('moderator', 'supervisor')
        ");

        // Create regular users view
        DB::statement("
            CREATE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `regular_users_view` AS
            SELECT * FROM `users` WHERE `role` = 'user'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `admins_view`");
        DB::statement("DROP VIEW IF EXISTS `regular_users_view`");
    }
};
