<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'donor', 'hospital'])->default('donor')->after('email');
            });
        }
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 30)->nullable()->after('role');
            });
        }
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('phone');
            });
        }

        if (!Schema::hasTable('donor_profiles')) {
            Schema::create('donor_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->string('blood_group', 5);
                $table->date('date_of_birth')->nullable();
                $table->string('gender', 20)->nullable();
                $table->string('address')->nullable();
                $table->string('city', 100)->nullable();
                $table->date('last_donation_date')->nullable();
                $table->boolean('eligible')->default(true);
                $table->string('emergency_contact', 30)->nullable();
                $table->timestamps();
                $table->index(['blood_group', 'eligible']);
            });
        }

        if (!Schema::hasTable('hospitals')) {
            Schema::create('hospitals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->string('hospital_name');
                $table->string('registration_no', 100)->unique();
                $table->string('contact_person')->nullable();
                $table->string('address')->nullable();
                $table->string('city', 100)->nullable();
                $table->string('phone', 30)->nullable();
                $table->enum('status', ['pending', 'approved', 'suspended'])->default('pending');
                $table->timestamps();
                $table->index(['city', 'status']);
            });
        }

        if (!Schema::hasTable('blood_inventory')) {
            Schema::create('blood_inventory', function (Blueprint $table) {
                $table->id();
                $table->string('blood_group', 5);
                $table->unsignedInteger('units')->default(0);
                $table->string('storage_location', 150)->nullable();
                $table->date('expiry_date')->nullable();
                $table->enum('status', ['available', 'reserved', 'expired'])->default('available');
                $table->timestamps();
                $table->index(['blood_group', 'status']);
            });
        }

        if (!Schema::hasTable('blood_donations')) {
            Schema::create('blood_donations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();
                $table->string('blood_group', 5);
                $table->unsignedInteger('units')->default(1);
                $table->date('donated_at');
                $table->enum('screening_status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['donor_id', 'donated_at']);
            });
        }

        if (!Schema::hasTable('blood_requests')) {
            Schema::create('blood_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->string('patient_name');
                $table->unsignedSmallInteger('patient_age')->nullable();
                $table->string('blood_group', 5);
                $table->unsignedInteger('units_required');
                $table->enum('urgency', ['normal', 'urgent', 'critical'])->default('normal');
                $table->dateTime('needed_by')->nullable();
                $table->text('reason')->nullable();
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'approved', 'fulfilled', 'rejected', 'cancelled'])->default('pending');
                $table->timestamps();
                $table->index(['blood_group', 'status', 'urgency']);
            });
        }

        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();
                $table->dateTime('appointment_at');
                $table->string('location', 150)->nullable();
                $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['donor_id', 'appointment_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('blood_requests');
        Schema::dropIfExists('blood_donations');
        Schema::dropIfExists('blood_inventory');
        Schema::dropIfExists('hospitals');
        Schema::dropIfExists('donor_profiles');

        if (Schema::hasColumn('users', 'is_active')) Schema::table('users', fn(Blueprint $table) => $table->dropColumn('is_active'));
        if (Schema::hasColumn('users', 'phone')) Schema::table('users', fn(Blueprint $table) => $table->dropColumn('phone'));
        if (Schema::hasColumn('users', 'role')) Schema::table('users', fn(Blueprint $table) => $table->dropColumn('role'));
    }
};
