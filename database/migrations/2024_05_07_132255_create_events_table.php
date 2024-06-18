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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('settings'); // whole day or half day
            $table->string('consequence')->nullable(); // community service hours rendered
            $table->date('event_date');
            $table->string("morning_login_start")->nullable();
            $table->string("morning_login_finish")->nullable();
            $table->string("morning_logout_start")->nullable();
            $table->string("morning_logout_finish")->nullable();
            $table->string("afternoon_login_start")->nullable();
            $table->string("afternoon_login_finish")->nullable();
            $table->string("afternoon_logout_start")->nullable();
            $table->string("afternoon_logout_finish")->nullable();
            $table->boolean('is_turn_on')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};