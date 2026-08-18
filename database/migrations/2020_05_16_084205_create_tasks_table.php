<?php

declare(strict_types=1);

use App\Http\Infrastructure\Interfaces\Tasks\TaskStatusInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', static function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('description');
            $table->string('status', 20)
                ->default(TaskStatusInterface::TASK_STATUS_VIEW)
                ->index();
            $table->bigInteger('user')->unsigned()->default(1);
            $table
                ->foreign('user')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
}
