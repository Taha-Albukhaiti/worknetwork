<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('portfolio_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained();
            $table->enum('type', ['responsibility', 'achievement', 'skill', 'description'])->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('portfolio_details');
    }
};