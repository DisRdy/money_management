<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Transaction creator
            $table->foreignId('category_id')->constrained()->onDelete('restrict'); // Prevent category deletion if used
            $table->enum('type', ['income', 'expense']); // Transaction type
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->date('transaction_date'); // Date of transaction
            $table->text('description'); // Transaction description
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
