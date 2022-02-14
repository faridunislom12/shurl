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
            $table->id();
            $table->string('last_name')->comment('Фамилия');
            $table->string('name')->comment('Имя');
            $table->string('middle_name')->nullable()->comment('Отчество');
            $table->string('birthdate')->nullable()->comment('Дата рождения');
            $table->string('email')->nullable()->comment('E-mail');
            $table->string('phone')->nullable()->comment('Телефон');
            $table->string('address')->nullable()->comment('Адрес');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->json('avatar')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
