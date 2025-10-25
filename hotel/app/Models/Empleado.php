<?php
// app/Models/Empleado.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Empleado extends Model
{

    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'user_id',
        'numero_empleado',
        'puesto',
        'fecha_contratacion',
        'salario',
        'turno',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
        'salario' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($empleado) {
            if (empty($empleado->numero_empleado)) {
                $empleado->numero_empleado = 'EMP' . strtoupper(Str::random(6));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRecepcionista()
    {
        return $this->puesto === 'recepcionista';
    }

    public function isAdministrador()
    {
        return $this->puesto === 'administrador';
    }

    public function isLimpieza()
    {
        return $this->puesto === 'limpieza';
    }

    public function isGerente()
    {
        return $this->puesto === 'gerente';
    }

    public function isActivo()
    {
        return $this->estado === 'activo';
    }
}