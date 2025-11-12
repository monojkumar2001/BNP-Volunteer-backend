<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Titles
            $table->string('title_en');
            $table->string('title_bn')->nullable();

            // Short Descriptions
            $table->text('short_description_en')->nullable();
            $table->text('short_description_bn')->nullable();

            // Full Descriptions (single page)
            $table->longText('description_en')->nullable();
            $table->longText('description_bn')->nullable();

            // Event Date & Time
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();

            // Location
            $table->string('location_en')->nullable();
            $table->string('location_bn')->nullable();

            // Thumbnail and Video
            $table->string('image')->nullable(); // main image
            $table->string('video_url')->nullable(); // short video (for modal)

            // Slug for single page
            $table->string('slug')->unique();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
