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

class MigrationCar14 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('car', function (Blueprint $table) {
		    if (!Schema::hasColumn("car", 'enable_external')) {
			    $table->string('enable_external')->default('off');
		    }
		    if (!Schema::hasColumn("car", 'use_external_link')) {
			    $table->longText('use_external_link')->default('');
		    }

		    if (!Schema::hasColumn("car", 'text_external_link')) {
			    $table->longText('text_external_link')->default('');
		    }
	    });
    }

    public function down()
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn(['enable_external']);
	        $table->dropColumn(['use_external_link']);
	        $table->dropColumn(['text_external_link']);
        });
    }
}
