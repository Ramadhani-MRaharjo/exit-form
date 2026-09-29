<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 50)->nullable()->unique()->after('id');
            $table->string('department')->nullable()->after('name');
            $table->enum('type', ['employee', 'approver', 'admin'])->default('employee')->after('email');
            $table->boolean('allowed')->default(true)->after('type');
            $table->foreignId('approver_id')->nullable()->after('allowed')->constrained('users')->nullOnDelete();
        });

        Schema::table('handover_forms', function (Blueprint $table) {
            $table->text('urgent_activity')->nullable()->after('notes');
            $table->text('urgent_document')->nullable()->after('urgent_activity');
            $table->text('routine_activity')->nullable()->after('urgent_document');
            $table->text('routine_document')->nullable()->after('routine_activity');
            $table->text('project_name')->nullable()->after('routine_document');
            $table->text('project_document')->nullable()->after('project_name');
            $table->text('additional_notes')->nullable()->after('project_document');
        });
    }

    public function down(): void
    {
        Schema::table('handover_forms', function (Blueprint $table) {
            $table->dropColumn([
                'urgent_activity', 'urgent_document', 'routine_activity',
                'routine_document', 'project_name', 'project_document', 'additional_notes',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropColumn(['nik', 'department', 'type', 'allowed', 'approver_id']);
        });
    }
};