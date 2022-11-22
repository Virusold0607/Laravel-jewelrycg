<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->integer('step_type')->default(0)->after('meta_description');
            $table->integer('step_group')->nullable()->default(0)->after('step_type');
            $table->string('steps')->nullable()->default('')->after('step_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('step_type');
            $table->dropColumn('step_group');
            $table->dropColumn('steps');
        });
    }
};
