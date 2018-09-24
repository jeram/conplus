<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            
            $table->text('description')->nullable();
            $table->integer('ordered')->nullable();
            $table->integer('delivered')->nullable();
            $table->integer('trade_status_id')->nullable();
            $table->decimal('capital', 11, 2)->nullable();
            $table->decimal('paid_amount', 11, 2)->nullable();
            $table->datetime('payment_date')->nullable();
            $table->string('attachment_filename')->nullable();

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
        Schema::dropIfExists('trades');
    }
}
