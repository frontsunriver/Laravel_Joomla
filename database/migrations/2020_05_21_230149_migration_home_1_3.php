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
use Illuminate\Support\Facades\DB;

class MigrationHome13 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home', function (Blueprint $table) {
            if (!Schema::hasColumn("home", 'enable_extra_guest')) {
                $table->string('enable_extra_guest', 3)->default('off');
            }
            if (!Schema::hasColumn("home", 'extra_guest_price')) {
                $table->float('extra_guest_price', 15, 6)->default(0);
            }
            if (!Schema::hasColumn("home", 'apply_to_guest')) {
                $table->integer('apply_to_guest')->default(1);
            }
        });
    }

    public function down()
    {
        Schema::table('home', function (Blueprint $table) {
            $table->dropColumn(['enable_extra_guest', 'extra_guest_price', 'apply_to_guest']);
        });
    }
}
