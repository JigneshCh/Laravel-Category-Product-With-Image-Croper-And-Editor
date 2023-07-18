<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('display_order')->default(0);
            $table->string('lang_code')->default('en');
			
			$table->longtext('issue_detail')->nullable();
			$table->longtext('recommendation')->nullable();
			$table->longtext('quote')->nullable();
			$table->float('default_price')->default(0);
			
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);

            $table->softDeletes();
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
        Schema::dropIfExists('categories');
    }
}
