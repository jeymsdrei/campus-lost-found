<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('found_items', function (Blueprint $table) {
            $table->enum('status', ['unclaimed', 'pending_claim', 'claimed', 'matched'])->default('unclaimed')->change();
        });
    }

    public function down(): void
    {
        Schema::table('found_items', function (Blueprint $table) {
            $table->enum('status', ['unclaimed', 'pending_claim', 'claimed'])->default('unclaimed')->change();
        });
    }
};
