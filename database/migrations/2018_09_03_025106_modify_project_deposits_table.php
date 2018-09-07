<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProjectDepositsTable extends Migration
{
    public function __construct()
    {
        \DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_deposits', function (Blueprint $table) {
            $table->dropColumn('status');
            
            $table->string('attachment_filename')->nullable()->change();
            $table->text('notes')->nullable()->change();
            $table->datetime('payment_date')->nullable()->after('amount');

            $table->integer('company_deposit_type_id')->unsigned();
            $table->foreign('company_deposit_type_id')->references('id')->on('company_deposit_types')->onDelete('cascade');
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
