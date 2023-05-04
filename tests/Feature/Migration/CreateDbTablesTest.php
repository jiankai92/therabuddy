<?php

namespace Tests\Feature\Migration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

//Import functions for testing
use App\Models\AiChat;
use App\Models\AiChatEntry;

class CreateDbTablesTest extends TestCase
{
    use DatabaseMigrations;

    private AiChat $chat_model;
    private array $db_columns;

    private function tableDataProvider(): array
    {
        $provider_data = [];
        $models = [
            new AiChat(),
            new AiChatEntry(),
        ];
        foreach ($models as $model) {
            // Reference columns based on model key, fillable and hidden properties defined in model
            $model_columns = array_merge(
                [$model->getKeyName()],
                $model->getFillable(),
                $model->getHidden()
            );
            $provider_data[] = [
                $model,
                $model_columns
            ];
        }
        return $provider_data;
    }

    /**
     * Check if ai_chats table is created
     * @group database-setup
     * @dataProvider tableDataProvider
     */
    public function test_ai_chat_table_created($model): void
    {
        $this->assertTrue(Schema::hasTable($model->getTable()));
    }

    /**
     * Check if ai_chats table is created with the required columns names
     * @group database-setup
     * @dataProvider tableDataProvider
     */
    public function test_ai_chat_columns_created($model, $model_columns): void
    {
        $this->assertEquals(
            Schema::getColumnListing($model->getTable()),
            $model_columns
        );
    }

}
