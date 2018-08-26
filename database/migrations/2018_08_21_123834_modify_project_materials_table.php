<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProjectMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_materials', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['project_material_status_id']);
        });

        Schema::table('project_materials', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable()->change();
            $table->integer('project_material_status_id')->nullable()->change();
            $table->decimal('price', 11, 2)->nullable()->change();
            $table->text('notes')->nullable()->change();
            $table->string('label')->nullable()->change();

            $table->decimal('total_price', 11, 2);
            $table->integer('to_order_qty')->nullable();
            $table->integer('warehouse_qty')->nullable();
            $table->integer('on_site_unused_qty')->nullable();
            $table->integer('used_qty')->nullable();
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
