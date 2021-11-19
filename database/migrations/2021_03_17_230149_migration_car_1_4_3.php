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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationCar143 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car', function (Blueprint $table) {
            if (Schema::hasColumn("car", 'location_address')) {
                $table->longText('location_address')->change();
            }
            if (Schema::hasColumn("car", 'location_state')) {
                $table->text('location_state')->change();
            }
            if (Schema::hasColumn("car", 'location_country')) {
                $table->text('location_country')->change();
            }
            if (Schema::hasColumn("car", 'location_city')) {
                $table->text('location_city')->change();
            }
        });
    }
}
