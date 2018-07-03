<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_details', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
			$table->string('address');
			$table->text('description');
			$table->date('proposal_date');
			$table->date('contract_date');
			$table->string('permit_number');
			$table->integer('project_type_id')->unsigned();
            $table->foreign('project_type_id')->references('id')->on('project_types')->onDelete('cascade');
			$table->integer('project_status_id')->unsigned();
            $table->foreign('project_status_id')->references('id')->on('project_statuses')->onDelete('cascade');
			$table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
			$table->date('actual_completion_date');
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
        Schema::dropIfExists('projects');
    }
}
