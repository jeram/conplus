<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_materials', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
			$table->integer('material_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
			$table->integer('vendor_id')->unsigned();
            $table->foreign('vendor_id')->references('id')->on('company_vendors')->onDelete('cascade');
			$table->integer('project_phase_id')->unsigned();
            $table->foreign('project_phase_id')->references('id')->on('project_phases')->onDelete('cascade');
			$table->integer('project_material_status_id')->unsigned();
            $table->foreign('project_material_status_id')->references('id')->on('project_material_statuses')->onDelete('cascade');
			$table->string('quantity');
			$table->decimal('price', 11, 2);
			$table->string('label');
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
        Schema::dropIfExists('project_materials');
    }
}
