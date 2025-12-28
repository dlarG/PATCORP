<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null');
            $table->string('driver_id', 20)->unique();
            $table->string('license_number', 50)->unique();
            $table->date('license_expiry');
            $table->enum('vehicle_type', ['car', 'motorcycle', 'truck', 'van']);
            $table->string('vehicle_plate', 20);
            $table->text('address')->nullable();
            $table->string('emergency_contact', 50)->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active');
            $table->enum('payment_status', ['paid', 'unpaid', 'pending'])->default('unpaid');
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};