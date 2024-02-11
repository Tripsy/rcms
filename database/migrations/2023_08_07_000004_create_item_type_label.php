<?php

use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Enums\ProjectLabelType;
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
        Schema::create('item_type_label', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->id();

            $table->bigInteger('item_type_id', false, true);
            $table->char('label_key', 64);
            $table->char('label_name', 255);
            $table->text('label_info');
            $table->enum('label_type', ProjectLabelType::justKeys())->default(ProjectLabelType::TEXT->value);
            $table->json('label_options');
            $table->enum('is_required', DefaultOption::justKeys())->default(DefaultOption::NO->value);
            $table->enum('is_html', DefaultOption::justKeys())->default(DefaultOption::NO->value);

            $table->enum('status', CommonStatus::justKeys())->default(CommonStatus::ACTIVE->value);

            $table->dateTime('created_at');
            $table->bigInteger('created_by', false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by', false, true)->nullable();

            $table->foreign('item_type_id')->references('id')->on('item_type')->onUpdate('no action')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_type_label');
    }
};
