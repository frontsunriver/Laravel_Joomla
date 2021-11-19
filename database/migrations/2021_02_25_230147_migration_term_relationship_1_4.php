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

    class MigrationTermRelationship14 extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('term_relation', function (Blueprint $table) {
                if (!Schema::hasColumn("term_relation", 'post_type')) {
                    $table->string('post_type')->default('post');
                }
            });
        }

    }
