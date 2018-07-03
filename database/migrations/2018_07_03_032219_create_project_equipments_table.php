<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_equipments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
			$table->integer('company_equipment_id')->unsigned();
            $table->foreign('company_equipment_id')->references('id')->on('company_equipments')->onDelete('cascade');
			$table->integer('company_equipment_status_id')->unsigned();
            $table->foreign('company_equipment_status_id')->references('id')->on('company_equipment_statuses')->onDelete('cascade');
			$table->text('notes');
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
        Schema::dropIfExists('project_equipments');
    }
}
