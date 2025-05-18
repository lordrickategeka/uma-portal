<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCancelledStatusToInstallmentPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For MySQL databases
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `installment_plans` MODIFY COLUMN `status` ENUM('pending','active','completed','failed','cancelled') NOT NULL");
        } 
        // For PostgreSQL databases (if you're using PostgreSQL)
        else if (DB::connection()->getDriverName() === 'pgsql') {
            // PostgreSQL doesn't have ENUM types in the same way MySQL does
            // First, add the 'cancelled' type to the existing check constraint
            DB::statement("ALTER TABLE installment_plans DROP CONSTRAINT IF EXISTS installment_plans_status_check");
            DB::statement("ALTER TABLE installment_plans ADD CONSTRAINT installment_plans_status_check CHECK (status IN ('pending', 'active', 'completed', 'failed', 'cancelled'))");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // For MySQL databases
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `installment_plans` MODIFY COLUMN `status` ENUM('pending','active','completed','failed') NOT NULL");
        } 
        // For PostgreSQL databases
        else if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE installment_plans DROP CONSTRAINT IF EXISTS installment_plans_status_check");
            DB::statement("ALTER TABLE installment_plans ADD CONSTRAINT installment_plans_status_check CHECK (status IN ('pending', 'active', 'completed', 'failed'))");
        }
    }
}