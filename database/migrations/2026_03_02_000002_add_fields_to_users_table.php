<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('gender')->default(0)->after('password');
            $table->date('dob')->nullable()->after('gender');
            $table->tinyInteger('level')->default(0)->after('dob');
            $table->integer('glutes_balance')->default(0)->after('level');
            $table->integer('reliability_score')->default(100)->after('glutes_balance');
            $table->string('image_path')->nullable()->after('reliability_score');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'dob', 'level', 'glutes_balance', 'reliability_score', 'image_path']);
            $table->dropSoftDeletes();
        });
    }
};
