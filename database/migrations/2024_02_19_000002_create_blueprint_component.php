<?php

use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;
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
        Schema::create('blueprint_component', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->id();

            $table->bigInteger('project_blueprint_id', false, true);
            $table->char('name', 64);
            $table->char('description', 255);
            $table->text('info')->nullable();
            $table->enum('component_type', BlueprintComponentType::justKeys())
                ->default(BlueprintComponentType::TEXT->value);
            $table->enum('component_format', BlueprintComponentFormat::justKeys())
                ->default(BlueprintComponentFormat::TEXT->value);
            $table->json('type_options');
            $table->enum('is_required', DefaultOption::justKeys())->default(DefaultOption::NO->value);
            $table->enum('status', CommonStatus::justKeys())->default(CommonStatus::ACTIVE->value);

            $table->dateTime('created_at');
            $table->bigInteger('created_by', false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by', false, true)->nullable();

            $table->unique(['project_blueprint_id', 'name']);

            $table->foreign('project_blueprint_id')
                ->references('id')
                ->on('project_blueprint')
                ->onUpdate('no action')
                ->onDelete('cascade');

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
        Schema::dropIfExists('blueprint_component');
    }
};
