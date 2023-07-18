<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue', function (Blueprint $table) {
            $table->increments('id');
			
			$table->integer('survey_id')->nullable()->default(0);
			$table->integer('surveyor_id')->nullable()->default(0);
			$table->string('unique_id',191)->nullable()->default(0);
			$table->integer('local_id')->nullable()->default(0);
			
			$table->integer('category_id')->nullable()->default(0);
			$table->integer('child_category_id')->nullable()->default(0);
			 
			
			
			$table->float('unit_cost')->nullable();
			$table->float('number_of_unit')->nullable();
			$table->float('total_cost')->nullable();
			
			$table->string('location',255)->nullable();
			
			$table->longtext('img_hint')->nullable();
			$table->longtext('issue_detail')->nullable();
			$table->longtext('recommendation')->nullable();
			$table->longtext('quote')->nullable();
			$table->longtext('note')->nullable();
			
			$table->timestamp('created')->nullable();
			$table->enum('status', ['new','open','cancelled','closed'])->default('new');
			
			
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
        Schema::dropIfExists('issue');
    }
}
