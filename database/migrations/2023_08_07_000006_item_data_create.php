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
        Schema::create('item_data', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->id();

            $table->uuid();
            $table->char('label', 64);
            $table->text('content');
            $table->enum('is_active', DefaultOption::justKeys())->default(DefaultOption::YES->value);

            $table->dateTime('created_at');
            $table->bigInteger('created_by',false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by',false, true)->nullable();

            $table->foreign('uuid')->references('uuid')->on('item')->onUpdate('no action')->onDelete('cascade');
            $table->index(['uuid', 'label', 'is_active']);
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_data');
    }
};
