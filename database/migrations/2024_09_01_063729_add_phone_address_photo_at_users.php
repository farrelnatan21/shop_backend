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
            $table->string('phone')->nullable()->after('password');
            $table->string('address')->nullable()->after('phone');
            //country
            $table->string('country')->nullable()->after('phone');
            //province
            $table->string('province')->nullable()->after('country');
            //city
            $table->string('city')->nullable()->after('province');
            //disctrict
            $table->string('district')->nullable()->after('city');
            //zipcode
            $table->string('postal_code')->nullable()->after('district');
            //roles
            $table->string('roles')->nullable()->after('postal_code');
            //photo
            $table->string('photo')->nullable()->after('roles');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'country', 'province', 'city', 'district', 'postal_code', 'roles', 'photo']);
        });
    }
};
