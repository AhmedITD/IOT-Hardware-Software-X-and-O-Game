<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gameturn', function (Blueprint $table)
        {
            $table->id();
            $table->string('round')->default('x');
            $table->string('permit')->default('0');
            $table->string('realTimeInfo')->default('0');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('gameturn');
    }
};
