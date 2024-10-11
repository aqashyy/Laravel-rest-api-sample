<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias')->unique();
            $table->string('linkaddress');
            $table->string('target');
            $table->string('viewtype');
            $table->string('type');
            $table->string('parent_id');
            $table->string('ordering')->default('1000');
            $table->string('menuorderid');
            $table->string('state')->default('1');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('type')->references('id')->on('menu_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
