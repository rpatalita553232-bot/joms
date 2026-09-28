<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('cust_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('contact_number', 20);
            $table->string('address', 255);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('created_by', 'fk_customers_created_by')->references('id')->on('users')->onDelete('restrict');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('vehicle_id');
            $table->unsignedInteger('cust_id');
            $table->string('plate_number', 20)->unique();
            $table->string('make', 100);
            $table->string('engine_model', 100);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('cust_id', 'fk_vehicles_customer')->references('cust_id')->on('customers')->onDelete('cascade');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->increments('staff_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('staff_first', 100);
            $table->string('staff_middle', 100)->nullable();
            $table->string('staff_last', 100);
            $table->string('contact_number', 20);
            $table->string('address', 255);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id', 'fk_staff_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by', 'fk_staff_created_by')->references('id')->on('users')->onDelete('restrict');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('services', function (Blueprint $table) {
            $table->increments('service_id');
            $table->string('job_desc', 255);
            $table->decimal('price', 10, 2);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('job_orders', function (Blueprint $table) {
            $table->increments('job_order_id');
            $table->unsignedInteger('cust_id');
            $table->unsignedInteger('vehicle_id');
            $table->unsignedBigInteger('created_by');
            $table->text('problem_description');
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'completed'])->default('pending');
            $table->date('date_issued');
            $table->date('expected_cmpl_date');
            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('cust_id', 'fk_joborders_customer')->references('cust_id')->on('customers')->onDelete('restrict');
            $table->foreign('vehicle_id', 'fk_joborders_vehicle')->references('vehicle_id')->on('vehicles')->onDelete('restrict');
            $table->foreign('created_by', 'fk_joborders_created_by')->references('id')->on('users')->onDelete('restrict');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('job_order_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_order_id');
            $table->unsignedInteger('service_id');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('job_order_id', 'fk_jos_joborder')->references('job_order_id')->on('job_orders')->onDelete('cascade');
            $table->foreign('service_id', 'fk_jos_service')->references('service_id')->on('services')->onDelete('restrict');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('job_order_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_order_id');
            $table->unsignedInteger('staff_id');
            $table->unsignedBigInteger('assigned_by');
            $table->date('assigned_date');
            $table->string('remarks', 255)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('job_order_id', 'fk_joa_joborder')->references('job_order_id')->on('job_orders')->onDelete('cascade');
            $table->foreign('staff_id', 'fk_joa_staff')->references('staff_id')->on('staff')->onDelete('restrict');
            $table->foreign('assigned_by', 'fk_joa_assigned_by')->references('id')->on('users')->onDelete('restrict');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('job_order_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_order_id');
            $table->unsignedBigInteger('approved_by');
            $table->enum('status', ['approved', 'rejected']);
            $table->text('remarks')->nullable();
            $table->dateTime('action_date');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->foreign('job_order_id', 'fk_joap_joborder')->references('job_order_id')->on('job_orders')->onDelete('cascade');
            $table->foreign('approved_by', 'fk_joap_approved_by')->references('id')->on('users')->onDelete('restrict');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_order_approvals');
        Schema::dropIfExists('job_order_assignments');
        Schema::dropIfExists('job_order_services');
        Schema::dropIfExists('job_orders');
        Schema::dropIfExists('services');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
    }
};
