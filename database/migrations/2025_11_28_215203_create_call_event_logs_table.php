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
        Schema::create('call_event_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_id');
            $table->string('event_type');

            $table->timestamp('event_timestamp');

            $table->longText('payload');

            $table->timestamp('created_time')->useCurrent();

            $table->unique(['call_id', 'event_type', 'event_timestamp']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_event_logs');
    }
};
