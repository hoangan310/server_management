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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('type', 50)->comment('VPS, Dedicated, Cloud');
            $table->string('ip')->index();
            $table->string('credentials_path')->nullable();
            $table->unsignedInteger('cpu')->nullable();
            $table->unsignedInteger('memory')->nullable();
            $table->unsignedInteger('disk_space')->nullable();
            $table->unsignedInteger('disk_space_left')->nullable();
            $table->unsignedInteger('bandwidth')->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->string('provider')->nullable();
            $table->string('status');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
