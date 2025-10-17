<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique('unique_tag_name');
            $table->index('name', 'idx_tag_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag');
    }
};
