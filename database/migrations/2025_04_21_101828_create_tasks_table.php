<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->datetime('deadline'); 
            $table->enum('status', ['on_progress', 'completed'])->default('on_progress'); 
            $table->enum('priority', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
