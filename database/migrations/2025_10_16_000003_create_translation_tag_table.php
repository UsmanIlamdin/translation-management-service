<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('translation_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('translation_id', 'fk_translation_tag_translation')
                ->references('id')->on('translation')
                ->onDelete('cascade');

            $table->foreign('tag_id', 'fk_translation_tag_tag')
                ->references('id')->on('tag')
                ->onDelete('cascade');

            $table->primary(['translation_id', 'tag_id'], 'pk_translation_tag');

            $table->index('translation_id', 'idx_translation_tag_translation_id');
            $table->index('tag_id', 'idx_translation_tag_tag_id');

            $table->index(['tag_id', 'translation_id'], 'idx_tag_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_tag');
    }
};
