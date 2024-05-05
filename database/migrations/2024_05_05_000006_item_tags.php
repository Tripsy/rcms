<?php

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
        Schema::create('item_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->id();

            $table->bigInteger('item_id', false, true);
            $table->bigInteger('tags_id', false, true);

            $table->dateTime('created_at');
            $table->bigInteger('created_by', false, true)->nullable();

            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->onUpdate('no action')
                ->onDelete('cascade');

            $table->foreign('tags_id')
                ->references('id')
                ->on('tags')
                ->onUpdate('no action')
                ->onDelete('cascade');

            $table->foreign('created_by')
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
        Schema::dropIfExists('item_tags');
    }
};
