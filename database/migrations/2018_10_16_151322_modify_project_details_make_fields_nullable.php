<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProjectDetailsMakeFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('project_details', function (Blueprint $table) {
            $table->integer('customer_id')->nullable()->change();
			$table->string('address')->nullable()->change();
			$table->text('description')->nullable()->change();
			$table->date('proposal_date')->nullable()->change();
			$table->date('contract_date')->nullable()->change();
			$table->string('permit_number')->nullable()->change();
			$table->integer('project_type_id')->unsigned()->nullable()->change();
			$table->integer('project_status_id')->unsigned()->nullable()->change();
			$table->integer('company_id')->unsigned()->nullable()->change();
            $table->date('actual_completion_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
