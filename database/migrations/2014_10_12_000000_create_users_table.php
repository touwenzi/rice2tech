<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('username')->comment('用户名')->unique();
            $table->string('email')->comment('邮箱')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 11)->comment('手机号')->unique()->nullable();
            $table->string('password')->comment('密码')->nullable();
            $table->dateTime('birth')->comment('出生年月')->nullable();
            $table->string('profile')->comment('个人信息')->nullable();
            $table->string('avatar')->comment('头像')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
