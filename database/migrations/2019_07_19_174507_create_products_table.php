<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Product;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->enum('status', [
                Product::BLOCKED,
                Product::PENDING,
                Product::APPROVED,
            ])->default(Product::PENDING);

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('main_image')->nullable();

            $table->timestamps();
            $table->dateTime('published_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
