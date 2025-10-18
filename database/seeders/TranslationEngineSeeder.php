<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TranslationEngineSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $locales = ['en', 'fr', 'es'];
        $tags = ['mobile', 'desktop', 'web', 'admin'];

        // Disable FK checks and query logging for performance
        DB::statement('SET foreign_key_checks=0;');
        DB::disableQueryLog();

        echo "Starting seeding...\n";

        // Step 1: Insert Tags
        DB::table('tag')->truncate();
        $tagIds = [];
        foreach ($tags as $tag) {
            $tagIds[] = DB::table('tag')->insertGetId([
                'name' => $tag,
            ]);
        }
        echo "Tags inserted: " . count($tagIds) . "\n";

        // Step 2: Insert Translations in chunks
        DB::table('translation')->truncate();
        DB::table('translation_tag')->truncate();

        $total = 100_000;
        $chunkSize = 5_000;
        $inserted = 0;
        $translationIdStart = 1;

        while ($inserted < $total) {
            $batch = [];
            for ($i = 0; $i < $chunkSize && $inserted < $total; $i++, $inserted++) {
                $batch[] = [
                    'locale' => $faker->randomElement($locales),
                    'key' => 'key_' . uniqid() . '_' . $faker->lexify('????'),
                    'content' => $faker->sentence(8),
                ];
            }

            DB::table('translation')->insert($batch);
            echo "Inserted $inserted translations...\n";

            $translationIds = range($translationIdStart, $translationIdStart + count($batch) - 1);
            $translationIdStart += count($batch);

            // Step 3: Create Pivot Links per chunk
            $pivotBatch = [];
            foreach ($translationIds as $translationId) {
                // Assign 1–3 random tags per translation
                $assignedTags = $faker->randomElements($tagIds, rand(1, 3));
                foreach ($assignedTags as $tagId) {
                    $pivotBatch[] = [
                        'translation_id' => $translationId,
                        'tag_id' => $tagId,
                    ];
                }
            }

            // Insert pivot records in smaller chunks to avoid memory overflow
            $pivotChunks = array_chunk($pivotBatch, 1000);
            foreach ($pivotChunks as $chunk) {
                DB::table('translation_tag')->insert($chunk);
            }

            echo "Inserted pivot records for this batch.\n";
        }

        echo "Total translations inserted: $total\n";
        echo "Seeding completed successfully.\n";

        DB::statement('SET foreign_key_checks=1;');
    }
}
