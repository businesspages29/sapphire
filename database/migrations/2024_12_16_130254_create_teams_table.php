<?php

use App\Models\GameMatch;
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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GameMatch::class)->cascadeOnDelete();
            $table->string('name');
            // $table->unsignedInteger('round')->default(0);
            // $table->boolean('is_round_1')->default(false);
            // $table->boolean('is_round_2')->default(false);
            // $table->boolean('is_round_3')->default(false);
            // $table->boolean('is_round_4')->default(false);
            $table->enum('round_1', ['win', 'lose'])->nullable();
            $table->enum('round_2', ['win', 'lose'])->nullable();
            $table->enum('round_3', ['win', 'lose'])->nullable();
            $table->enum('round_4', ['win', 'lose'])->nullable();
            $table->enum('result', ['win', 'lose'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
