<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 50)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_number', 100)->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('position')->nullable();
            $table->date('joined_at')->nullable();
            $table->date('left_at')->nullable();
            $table->timestamps();
        });

        Schema::create('handover_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('form_number', 100)->unique();
            $table->date('handover_date');
            $table->date('effective_exit_date')->nullable();
            $table->string('reason')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'completed'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('handover_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('handover_form_id')->constrained()->cascadeOnDelete();
            $table->string('category', 100)->nullable();
            $table->string('item_name');
            $table->string('asset_code', 100)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('condition_before', 100)->nullable();
            $table->string('condition_after', 100)->nullable();
            $table->boolean('is_returned')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('handover_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('handover_form_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['employee', 'supervisor', 'hr', 'it', 'finance', 'other']);
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();

            $table->unique(['handover_form_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handover_signatures');
        Schema::dropIfExists('handover_items');
        Schema::dropIfExists('handover_forms');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
};