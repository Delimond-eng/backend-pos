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
        Schema::create('expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->useCurrent();
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->foreignId('expense_type_id')->constrained('expense_types');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_types');
    }
};
