<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('found_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->text('description');
            $table->string('category', 100);
            $table->date('date_found');
            $table->string('location');
            $table->string('image')->nullable();
            $table->enum('status', ['unclaimed', 'pending_claim', 'claimed'])->default('unclaimed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('found_items');
    }
};
