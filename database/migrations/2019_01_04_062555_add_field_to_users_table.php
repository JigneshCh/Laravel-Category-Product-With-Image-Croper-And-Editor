<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			
			if(!\Schema::hasColumn("users", "first_name")){
				$table->string('first_name')->nullable();
			}
			if(!\Schema::hasColumn("users", "last_name")){
				$table->string('last_name')->nullable();
			}
			if(!\Schema::hasColumn("users", "phone_number")){
				$table->string('phone_number')->nullable();
			}
			if(!\Schema::hasColumn("users", "language")){
				$table->string('language')->nullable()->default('he');
			}
			if(!\Schema::hasColumn("users", "device_token")){
				$table->string('device_token',300)->nullable()->default(null);
			}
			if(!\Schema::hasColumn("users", "device_type")){
				$table->enum('device_type', ['android','ios','web'])->default('android');
			}
			if(!\Schema::hasColumn("users", "status")){
				$table->enum('status', ['active','inactive'])->default('active');
			}
			if(!\Schema::hasColumn("users", "utype")){
				$table->enum('utype', ['admin','employee'])->default('employee');
			}
			if(!\Schema::hasColumn("users", "otp_token")){
				$table->string('otp_token')->nullable();
			}
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
