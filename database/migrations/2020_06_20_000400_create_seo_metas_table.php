<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('seo_url')->nullable()
                ->comment('Defines the SEO URL of the specified meta data if the meta data is url based.');
            $table->string('model')->nullable()
                ->comment('Defines the related database model namespace of the specified meta data, if the meta data is model based.');
            $table->unsignedBigInteger('foreign_key')->nullable()
                ->comment('The primary key of related database model. This column is required if the meta data is model based.');
            $table->string('locale', 8)->nullable()
                ->comment('The locale of specified meta data.');
            $table->string('seo_title', 60)
                ->comment('The SEO title value of the specified meta data.');
            $table->string('seo_description', 160)
                ->comment('The SEO description value of the specified meta data.');
            $table->string('open_graph_type', 32)
                ->comment('The open graph type of the specified meta data. The open graph type options are: `article` and `website`');
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
        Schema::dropIfExists('seo_metas');
    }
}
