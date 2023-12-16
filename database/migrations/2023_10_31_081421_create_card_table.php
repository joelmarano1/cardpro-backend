<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card', function (Blueprint $table) {
            $table->id();
            $table->string('card_series')->nullable();
            $table->string('user_email');
            $table->string('user_photo')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->string('pronouns')->nullable();
            $table->string('company')->nullable();
            $table->string('designation')->nullable();
            $table->text('summary')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('work_address')->nullable();
            $table->string('font_style')->nullable();
            $table->string('title_size')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('auto_generated')->nullable();
            $table->string('self_domain')->nullable();
            $table->string('enable_tcs')->nullable();
            

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
        Schema::dropIfExists('card');
    }
}
