<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPhrasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->integer('percentage_completed')->nullable()->after('notes');
            $table->integer('project_percentage')->nullable()->after('notes');
            $table->date('actual_start_date')->nullable()->after('notes');
            $table->date('actual_finish_date')->nullable()->after('notes');
            $table->boolean('notify_on_start_date')->nullable()->default(0)->after('notes');
            $table->boolean('notify_on_finish_date')->nullable()->default(0)->after('notes');

            $table->date('start_date')->nullable()->change();
            $table->date('finish_date')->nullable()->change();
            $table->text('notes')->nullable()->change();
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
