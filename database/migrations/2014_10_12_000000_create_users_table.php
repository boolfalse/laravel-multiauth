<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('status', [
                User::ACTIVE,
                User::NOT_ACTIVE,
            ])->default(User::NOT_ACTIVE);
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('birth_year')->nullable();
            $table->string('image')->nullable();
            $table->string('original_image_path')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
