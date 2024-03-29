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
        Schema::create('company_branch_members', function (Blueprint $table) {
            $table->id();
			$table->foreignId('user_id')->unsigned();
            $table->foreignId('company_branch_id')->unsigned();
            $table->unique(['user_id', 'company_branch_id']);
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
        Schema::dropIfExists('company_branch_members');
    }
};
