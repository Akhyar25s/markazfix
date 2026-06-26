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
            if (Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }
            if (Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
            if (!Schema::hasColumn('users', 'umur')) {
                $table->integer('umur')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'asal_daerah')) {
                $table->string('asal_daerah')->nullable()->after('wilayah_id');
            }
        });

        // Update enum status to ['aktif', 'tamu', 'nonaktif']
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('aktif', 'tamu', 'nonaktif') DEFAULT 'aktif'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('umur');
            $table->dropColumn('asal_daerah');
            $table->date('tanggal_lahir')->nullable()->after('password');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('no_telepon');
        });

        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'");
        }
    }
};
