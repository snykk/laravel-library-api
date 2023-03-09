<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cms_admins', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('The identifier and primary key of cms admin records.');
            $table->string('name')->comment('The personal name of specified cms admin.');
            $table->string('email')->unique()->comment('The email address of specified cms admin. Email address should be unique for each admin.');
            $table->string('password')->comment('The hashed form of specified cms admin\'s password.');
            $table->rememberToken()->comment('Store the value of remember token, when the cms admin used `remember me` function upon login.');
            $table->softDeletes()
                ->comment('Indicate whether the record has been deleted. If the value is `null` then it is not deleted.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_admins');
    }
}
