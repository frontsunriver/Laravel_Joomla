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

class MigrationHome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->string('post_title');
            $table->string('post_slug');
            $table->longText('post_content')->nullable();
            $table->longText('post_description')->nullable();
            $table->bigInteger('author');
            $table->string('created_at', 20);
            $table->float('location_lat', 10, 6)->nullable();
            $table->float('location_lng', 10, 6)->nullable();
            $table->string('location_address')->nullable();
            $table->string('location_zoom')->nullable();
            $table->string('location_state')->nullable();
            $table->string('location_postcode', 15)->nullable();
            $table->string('location_country', 50)->nullable();
            $table->string('location_city', 50)->nullable();
            $table->string('gallery')->nullable();
            $table->string('thumbnail_id')->nullable();
            $table->integer('number_of_guest')->nullable();
            $table->integer('number_of_bedrooms')->nullable();
            $table->integer('number_of_bathrooms')->nullable();
            $table->float('size')->nullable();
            $table->integer('min_stay')->nullable();
            $table->integer('max_stay')->nullable();
            $table->string('booking_type', 20)->nullable();
            $table->float('base_price', 16, 5)->nullable();
            $table->float('weekend_price', 16, 5)->nullable();
            $table->string('weekend_to_apply')->nullable();
            $table->longText('extra_services')->nullable();
            $table->string('amenities')->nullable();
            $table->string('facilities')->nullable();
            $table->string('home_type')->nullable();
            $table->string('enable_cancellation', 10)->nullable();
            $table->integer('cancel_before')->nullable();
            $table->longText('cancellation_detail')->nullable();
            $table->string('checkin_time', 20)->nullable();
            $table->string('checkout_time', 20)->nullable();
            $table->float('rating', 8, 1)->nullable();
            $table->string('is_featured', 3)->nullable();
            $table->string('status');

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
        Schema::drop('home');
    }
}
