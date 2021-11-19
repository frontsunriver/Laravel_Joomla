<?php

/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.18
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2019, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationHomeBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_booking', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('booking_id');
            $table->string('booking_description')->nullable();
            $table->bigInteger('service_id');
            $table->float('total', 16, 5);
            $table->integer('number_of_guest');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100);
            $table->string('phone', 50);
            $table->string('address', 255);
            $table->bigInteger('buyer');
            $table->bigInteger('owner');
            $table->string('payment_type', 50);
            $table->longText('checkout_data');
            $table->string('token_code', 255);
            $table->string('currency', 255);
            $table->string('note', 500)->nullable();
            $table->bigInteger('start_date');
            $table->bigInteger('end_date');
            $table->bigInteger('start_time');
            $table->bigInteger('end_time');
            $table->bigInteger('created_date');
            $table->bigInteger('total_minutes');
            $table->string('status', 50);
            $table->string('confirm', 50)->nullable();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('home_booking');
    }
}
