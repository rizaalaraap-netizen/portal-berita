<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_hash', 64);
            $table->date('viewed_on');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'visitor_hash', 'viewed_on']);
            $table->index(['viewed_on', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
