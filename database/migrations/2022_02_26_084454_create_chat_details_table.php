<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_user_id')->comment('from user where user_role 1');
            $table->bigInteger('status')->comment('0= not_answer, 1= in_progress, 2 answerd, 3= spam');
            $table->text('message');
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
        Schema::dropIfExists('chat_details');
    }
}
