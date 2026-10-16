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
            $table->string('avatar')->nullable()->after('email');
            $table->string('phone')->nullable()->after('avatar');
            $table->string('position')->nullable()->after('phone');
            $table->foreignId('division_id')->nullable()->after('position')->constrained('divisions')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->after('division_id')->constrained('users')->nullOnDelete();
            $table->date('join_date')->nullable()->after('supervisor_id');
            $table->text('address')->nullable()->after('join_date');
            $table->boolean('is_active')->default(true)->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['division_id']);
            $table->dropColumn([
                'avatar',
                'phone',
                'position',
                'division_id',
                'supervisor_id',
                'join_date',
                'address',
                'is_active',
            ]);
        });
    }
};
