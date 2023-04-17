<?php

namespace Tests\Unit\migration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Types as DBALTypes;
use Tests\TestCase;

//Import functions for testing
use App\Models\AiChatModel;

class CreateAiChatTableTest extends TestCase
{
    use DatabaseMigrations;

    private AiChatModel $chat_model;
    private array $db_columns;

    public function setUp(): void
    {
        parent::setUp();
        $this->chat_model = new AiChatModel();

        // Reference columns based on fillable and hidden properties defined in model
        $this->db_columns = array_merge(
            [$this->chat_model->getKeyName()],
            $this->chat_model->getFillable(),
            $this->chat_model->getHidden()
        );

    }

    /**
     * Check if ai_chats table is created
     * @group database-setup
     */
    public function test_ai_chat_table_created(): void
    {
        $this->assertTrue(Schema::hasTable($this->chat_model->getTable()));
    }

    /**
     * Check if ai_chats table is created with the required columns names
     * @group database-setup
     */
    public function test_ai_chat_columns_created(): void
    {
        $this->assertEquals(
            Schema::getColumnListing($this->chat_model->getTable()),
            $this->db_columns
        );
    }

    /**
     * Check if ai_chats table is created with the required column types
     * @group database-setup
     */
    public function test_ai_chat_columns_type(): void
    {
        $table_name = $this->chat_model->getTable();
        foreach (Schema::getColumnListing($this->chat_model->getTable()) as $col) {
            switch ($col) {
                case 'id':
                    $this->assertSame(DBALTypes::BIGINT, Schema::getColumnType($table_name, $col));
                    break;
                case 'user_id':
                    $this->assertSame(DBALTypes::INTEGER, Schema::getColumnType($table_name, $col));
                    break;
                case 'session_id':
                    $this->assertSame(DBALTypes::STRING, Schema::getColumnType($table_name, $col));
                    break;
                case 'messages':
                    $this->assertSame(DBALTypes::JSON, Schema::getColumnType($table_name, $col));
                    break;
                case 'created_at':
                case 'updated_at':
                    $this->assertSame(DBALTypes::DATETIME_MUTABLE, Schema::getColumnType($table_name, $col));
                    break;
                default:
                    $this->fail('Unexpected DB column ' . $col);
            }
        }
    }

}
