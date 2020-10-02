<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('group_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_default')->default(0);
            $table->string('variation_conditions_mode');
            $table->integer('variation_conditions_value');
            $table->integer('display_duration');
            $table->integer('text_delay');
            $table->integer('text_duration');
            $table->string('background_color');
            $table->string('display_pattern');
            $table->string('image');
            $table->string('sound');
            $table->integer('volume');
            $table->string('header_template');
            $table->text('header_text');
            $table->text('message_text');
            $table->string('message_background');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variations');
    }
}
