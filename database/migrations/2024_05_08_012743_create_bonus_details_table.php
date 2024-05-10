<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bonus_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_id')->constrained();
            $table->foreignId('employee_id')->constrained();
            $table->unsignedSmallInteger('bonus_percentage');
            $table->unsignedDecimal('bonus', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_details');
    }
};
