<?php

use App\Enums\CommonStatus;
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
        Schema::create('project', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->id();

            $table->char('name', 255);
            $table->char('authority_name', 255)->index();
            $table->char('authority_key', 32);

            $table->enum('status', CommonStatus::justKeys())->default(CommonStatus::INACTIVE->value);

            $table->dateTime('created_at');
            $table->bigInteger('created_by',false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by',false, true)->nullable();

            $table->index(['authority_name', 'name']);

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_permissions');
        Schema::dropIfExists('project');
    }
};
