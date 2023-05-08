<?php

namespace Tests\Unit\migration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Types as DBALTypes;
use Tests\TestCase;

//Import functions for testing
use App\Models\AiChatEntry;

class AiChatEntryColumnTypeTest extends TestCase
{
    use DatabaseMigrations;

    private AiChatEntry $chat_model;
    private array $db_columns;

    public function setUp(): void
    {
        parent::setUp();
        $this->chat_model = new AiChatEntry();
    }

    /**
     * Check if ai_chat_entry table is created with the required column types
     * @group database-setup
     */
    public function test_created_ai_chat_columns_type(): void
    {
        $table_name = $this->chat_model->getTable();
        foreach (Schema::getColumnListing($table_name) as $col) {
            switch ($col) {
                case 'id':
                    $this->assertSame(DBALTypes::BIGINT, Schema::getColumnType($table_name, $col));
                    break;
                case 'chat_id':
                    $this->assertSame(DBALTypes::INTEGER, Schema::getColumnType($table_name, $col));
                    break;
                case 'type':
                    $this->assertSame(DBALTypes::STRING, Schema::getColumnType($table_name, $col));
                    break;
                case 'message':
                    $this->assertSame(DBALTypes::TEXT, Schema::getColumnType($table_name, $col));
                    break;
                case 'created_at':
                    $this->assertSame(DBALTypes::DATETIME_MUTABLE, Schema::getColumnType($table_name, $col));
                    break;
                default:
                    $this->fail('Unexpected DB column ' . $col);
            }
        }
    }

}
