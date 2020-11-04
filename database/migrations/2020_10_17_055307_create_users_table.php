<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->from(10000);
            $table->string('username', 255)->comment('用户名称')->unique();
            $table->string('real_name', 255)->comment('真实名称')->nullable($value = true);
            $table->string('password', 255)->comment('用户密码');
            $table->tinyInteger('sex')->comment('性别')->nullable($value = true);
            $table->string('gmail', 255)->comment('邮箱')->nullable($value = true);
            $table->softDeletes('deleted_at', 0);
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
