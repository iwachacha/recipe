<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('tag_id')->constrained()->nullable();
            $table->text('image_name')->nullable();
            $table->string('category');
            $table->string('quantity');
            $table->string('cooking_level');
            $table->string('cooking_time');
            $table->string('title');
            $table->text('ingredient');
            $table->text('method');
            $table->text('advice');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
};
