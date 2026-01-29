<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Actor who performed the action
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete(); // Owner of the affected record
            $table->string('action'); // created, updated, deleted
            $table->string('model_type'); // App\Models\Transaction
            $table->unsignedBigInteger('model_id'); // ID of the affected record
            $table->json('old_values')->nullable(); // State before change
            $table->json('new_values')->nullable(); // State after change
            $table->string('ip_address', 45)->nullable(); // IPv4/IPv6 support
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');

            // Indexes for performance
            $table->index(['tenant_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
