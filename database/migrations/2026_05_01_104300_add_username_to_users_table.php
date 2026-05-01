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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
        });

        // Backfill existing users: generate username from email prefix
        \App\Models\User::all()->each(function ($user) {
            $base = explode('@', $user->email)[0];
            $username = strtolower(preg_replace('/[^a-zA-Z0-9._-]/', '', $base));

            // Handle duplicates
            $original = $username;
            $counter = 1;
            while (\App\Models\User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $original . $counter;
                $counter++;
            }

            $user->update(['username' => $username]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
