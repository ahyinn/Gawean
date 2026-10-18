<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Format: "Jurusan · Kampus" (keduanya wajib terisi berpasangan)
            $table->string('campus')->nullable()->after('bio');
            // Daftar keahlian freelancer (array JSON, maks. 8 item)
            $table->json('skills')->nullable()->after('campus');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['campus', 'skills']);
        });
    }
};
