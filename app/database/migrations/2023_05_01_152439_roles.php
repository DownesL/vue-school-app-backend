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
//        Schema::create('roles', function (Blueprint $table) {
//            $table->id();
//            $table->enum('level',['USER','GROUP_ADMIN']);
//            $table->foreignId('organisation_id')->nullable()->constrained('organisations');
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
