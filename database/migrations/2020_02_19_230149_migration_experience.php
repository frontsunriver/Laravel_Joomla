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

class MigrationExperience extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->string('post_title');
            $table->string('post_slug');
            $table->longText('post_content')->nullable();
            $table->longText('post_description')->nullable();
            $table->bigInteger('author');
            $table->string('created_at', 20);
            $table->float('location_lat', 10, 6)->nullable()->default(48.856613);
            $table->float('location_lng', 10, 6)->nullable()->default(2.352222);
            $table->string('location_address')->nullable();
            $table->string('location_zoom')->nullable()->default(12);
            $table->string('location_state')->nullable();
            $table->string('location_postcode', 15)->nullable();
            $table->string('location_country', 50)->nullable();
            $table->string('location_city', 50)->nullable();
            $table->string('gallery')->nullable();
            $table->string('thumbnail_id')->nullable();
            $table->integer('number_of_guest')->nullable();
            $table->string('booking_type', 20)->nullable();
            $table->string('booking_form', 20)->nullable()->default('instant');
            $table->float('base_price', 16, 5)->nullable();
            $table->float('adult_price', 16, 5)->nullable();
            $table->float('child_price', 16, 5)->nullable();
            $table->float('infant_price', 16, 5)->nullable();
            $table->string('languages')->nullable();
            $table->string('durations')->nullable();
            $table->string('inclusions')->nullable();
            $table->string('exclusions')->nullable();
            $table->longText('itinerary')->nullable();
            $table->string('price_categories')->nullable();
            $table->string('price_primary')->nullable();
            $table->longText('video')->nullable();
            $table->longText('extra_services')->nullable();
            $table->longText('meeting_points')->nullable();
            $table->string('amenities')->nullable();
            $table->string('experience_type')->nullable();
            $table->string('enable_cancellation', 10)->nullable();
            $table->integer('cancel_before')->nullable();
            $table->longText('cancellation_detail')->nullable();
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
