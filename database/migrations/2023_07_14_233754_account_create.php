<?php

use App\Enums\AccountStatus;
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
        Schema::create('account', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->id();
            $table->string('email');
            $table->enum('status', AccountStatus::justKeys())->default(AccountStatus::ACTIVE->value);
            $table->dateTime('created_at');
            $table->bigInteger('created_by',false, true)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by',false, true)->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
