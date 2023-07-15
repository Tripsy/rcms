<?php

use App\Enums\ItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('item', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->id();
            $table->bigInteger('account_id', false, true);
            $table->text('description')->nullable();
            $table->enum('status', ItemStatus::justKeys())->default('pending');

            $table->dateTime('created_at');
            $table->bigInteger('created_by',false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by',false, true)->nullable();

            $table->foreign('account_id')->references('id')->on('account')->onUpdate('no action')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
