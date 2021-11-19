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

class MigrationHome122 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home', function (Blueprint $table) {
            if (!Schema::hasColumn("home", 'import_ical_url')) {
                $table->longText('import_ical_url')->nullable();
            }
            if (!Schema::hasColumn("home", 'price_7_day')) {
                $table->float('price_7_day', 15, 6)->default(0);
            }

            if (!Schema::hasColumn("home", 'price_14_day')) {
                $table->float('price_14_day', 15, 6)->default(0);
            }

            if (!Schema::hasColumn("home", 'price_30_day')) {
                $table->float('price_30_day', 15, 6)->default(0);
            }

            if (!Schema::hasColumn("home", 'use_long_price')) {
                $table->string('use_long_price', 10)->default('off');
            }

            if (Schema::hasColumn("home", 'post_title')) {
                $table->text('post_title')->change();
            }
            if (Schema::hasColumn("home", 'post_slug')) {
                $table->text('post_slug')->change();
            }

        });
    }

    public function down()
    {
        Schema::table('home', function (Blueprint $table) {
            $table->dropColumn(['import_ical_url']);
            $table->dropColumn(['price_7_day']);
            $table->dropColumn(['price_14_day']);
            $table->dropColumn(['price_30_day']);
            $table->dropColumn(['use_long_price']);
        });
    }
}
