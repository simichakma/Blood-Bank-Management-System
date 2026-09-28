<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('blood_inventory', 'hospital_id')) {
            Schema::table('blood_inventory', function (Blueprint $table) {
                $table->foreignId('hospital_id')->nullable()->after('id')->constrained('hospitals')->nullOnDelete();
                $table->index(['hospital_id', 'blood_group', 'status']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blood_inventory', 'hospital_id')) {
            Schema::table('blood_inventory', function (Blueprint $table) {
                $table->dropForeign(['hospital_id']);
                $table->dropIndex(['hospital_id', 'blood_group', 'status']);
                $table->dropColumn('hospital_id');
            });
        }
    }
};
