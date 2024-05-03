<?php

use App\Enums\DefaultOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_content', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->id();

            $table->bigInteger('blueprint_item_id', false, true);
            $table->bigInteger('blueprint_component_id', false, true);
            $table->text('content');
            $table->enum('is_active', DefaultOption::justKeys())->default(DefaultOption::YES->value);

            $table->dateTime('created_at');
            $table->bigInteger('created_by', false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by', false, true)->nullable();

            $table->foreign('blueprint_item_id')
                ->references('id')
                ->on('blueprint_item')
                ->onUpdate('no action')
                ->onDelete('cascade');

            $table->foreign('blueprint_component_id')
                ->references('id')
                ->on('blueprint_component')
                ->onUpdate('no action')
                ->onDelete('cascade');

            $table->index(['blueprint_item_id', 'blueprint_component_id', 'is_active'], 'item_content_active');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('no action')
                ->onDelete('set null');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onUpdate('no action')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_content');
    }
};
