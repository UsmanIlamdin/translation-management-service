<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->index('idx_translations_locale');
            $table->string('key')->index('idx_translations_key');
            $table->text('content');
            $table->timestamps();

            // Ensure locale + key uniqueness
            $table->unique(['locale', 'key'], 'uniq_translations_locale_key');
        });

        // Add FULLTEXT index for fast search on content
        DB::statement('ALTER TABLE translations ADD FULLTEXT idx_translations_content (content)');
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
