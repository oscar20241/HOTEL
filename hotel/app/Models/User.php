<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'direccion',
        'fecha_nacimiento',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'date',
    ];

    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class);
    }

    public function esEmpleado()
    {
        return $this->empleado !== null;
    }

    public function esHuesped()
    {
        return !$this->esEmpleado();
    }

    public function esRecepcionista()
    {
        return $this->empleado && $this->empleado->puesto === 'recepcionista';
    }

    public function esAdministrador()
    {
        return $this->empleado && $this->empleado->puesto === 'administrador';
    }

    public function esLimpieza()
    {
        return $this->empleado && $this->empleado->puesto === 'limpieza';
    }

    public function esGerente()
    {
        return $this->empleado && $this->empleado->puesto === 'gerente';
    }
}