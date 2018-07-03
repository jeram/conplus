<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_notes', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
			$table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
			$table->string('note');
			$table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('project_note_statuses')->onDelete('cascade');
			$table->integer('assigned_to')->unsigned();
            $table->foreign('assigned_to')->references('id')->on('users');
			$table->datetime('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_notes');
    }
}
