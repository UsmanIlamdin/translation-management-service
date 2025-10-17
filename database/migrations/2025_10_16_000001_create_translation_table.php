<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translation', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->index('idx_translation_locale');
            $table->string('key')->index('idx_translation_key');
            $table->text('content');

            $table->unique(['locale', 'key'], 'uniq_translation_locale_key');
        });

        DB::statement('ALTER TABLE translation ADD FULLTEXT idx_translation_content (content)');
    }

    public function down(): void
    {
        Schema::dropIfExists('translation');
    }
};
