<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('settings', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('The identifier and primary key of setting records.');
            $table->string('type')
                ->comment('The setting\'s data type. Currently supported data types are: `text`, `textarea`, `number`, and `email`');
            $table->string('key')->unique()->comment('The unique name of a setting');
            $table->text('value')->nullable()->comment('The content of a setting');
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
        Schema::dropIfExists('settings');
    }
}
