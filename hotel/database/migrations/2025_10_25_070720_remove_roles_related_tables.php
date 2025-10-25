<?php
// database/migrations/xxxx_xx_xx_xxxxxx_remove_roles_related_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRolesRelatedTables extends Migration
{
    public function up()
    {
        // Eliminar foreign key primero
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
        });
        
        // Eliminar tabla roles si existe
        Schema::dropIfExists('roles');
    }

    public function down()
    {
        // No hacemos rollback para simplificar
    }
}