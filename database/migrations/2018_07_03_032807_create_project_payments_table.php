<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_payments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
			$table->decimal('amount', 11, 2);
			$table->string('check_number');
			$table->datetime('payment_date');
			$table->text('notes');
			$table->integer('phase_id')->unsigned();
            $table->foreign('phase_id')->references('id')->on('project_phases')->onDelete('cascade');
			$table->integer('company_payment_type_id')->unsigned();
            $table->foreign('company_payment_type_id')->references('id')->on('company_payment_types')->onDelete('cascade');
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
        Schema::dropIfExists('project_payments');
    }
}
