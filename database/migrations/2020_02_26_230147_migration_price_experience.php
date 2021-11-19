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

class MigrationPriceExperience extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_price', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('experience_id');
            $table->bigInteger('start_time');
            $table->bigInteger('start_date');
            $table->bigInteger('end_time');
            $table->bigInteger('end_date');
            $table->integer('max_people');
            $table->float('adult_price', 16, 5);
            $table->float('child_price', 16, 5);
            $table->float('infant_price', 16, 5);
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
        Schema::drop('experience_price');
    }
}
