<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('folder_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('file_id')->nullable();
            $table->integer('preview_id')->nullable();
            $table->integer('version')->default(0)->nullable();
            $table->string('name');
            $table->string('type');
            $table->longText('content')->nullable();
            $table->string('subtype');
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('resources');
    }
}
