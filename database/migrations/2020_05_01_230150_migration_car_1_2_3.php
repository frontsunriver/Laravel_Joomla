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

class MigrationCar123 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('car', function (Blueprint $table) {
		    if (!Schema::hasColumn("car", 'post_type')) {
			    $table->string('post_type')->default('car');
		    }
		    if (!Schema::hasColumn("car", 'discount_by_day')) {
			    $table->text('discount_by_day')->nullable();
		    }

		    if (!Schema::hasColumn("car", 'insurance_plan')) {
			    $table->text('insurance_plan')->nullable();
		    }
	    });
    }

    public function down()
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn(['post_type']);
	        $table->dropColumn(['discount_by_day']);
	        $table->dropColumn(['insurance_plan']);
        });
    }
}
