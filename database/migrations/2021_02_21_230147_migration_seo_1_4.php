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

class MigrationSeo14 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->bigIncrements('seo_id');
            $table->bigInteger('post_id');
            $table->string('post_type');
            $table->longText('seo_title')->nullable();
            $table->longText('seo_description')->nullable();
            $table->integer('facebook_image')->nullable();
            $table->longText('facebook_title')->nullable();
            $table->longText('facebook_description')->nullable();
            $table->integer('twitter_image')->nullable();
            $table->longText('twitter_title')->nullable();
            $table->longText('twitter_description')->nullable();
            $table->string('created_at', 20);
            $table->engine = 'InnoDB';
        });
    }

    public function down()
    {
        Schema::drop('seo');
    }
}
