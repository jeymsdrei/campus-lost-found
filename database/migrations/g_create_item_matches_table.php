<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('found_item_id')->constrained()->onDelete('cascade');
            $table->decimal('match_score', 5, 2);
            $table->boolean('is_reviewed')->default(false);
            $table->timestamps();
            
            $table->unique(['lost_item_id', 'found_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_matches');
    }
};
