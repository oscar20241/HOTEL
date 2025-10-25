<?php
// app/Models/Habitacion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Habitacion extends Model
{
    use HasFactory;
    
    protected $table = 'habitaciones';

    protected $fillable = ['numero', 'tipo_habitacion_id', 'estado', 'caracteristicas'];

    public function tipoHabitacion()
    {
        return $this->belongsTo(TipoHabitacion::class);
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class);
    }

    public function estaDisponible($fechaEntrada, $fechaSalida)
    {
        // No disponible si está en mantenimiento
        if ($this->estado === 'mantenimiento') {
            return false;
        }

        // Verificar si hay reservaciones conflictivas
        return !$this->reservaciones()
            ->where(function($query) use ($fechaEntrada, $fechaSalida) {
                $query->whereBetween('fecha_entrada', [$fechaEntrada, $fechaSalida])
                      ->orWhereBetween('fecha_salida', [$fechaEntrada, $fechaSalida])
                      ->orWhere(function($q) use ($fechaEntrada, $fechaSalida) {
                          $q->where('fecha_entrada', '<=', $fechaEntrada)
                            ->where('fecha_salida', '>=', $fechaSalida);
                      });
            })
            ->whereIn('estado', ['confirmada', 'activa', 'pendiente'])
            ->exists();
    }

    public function getPrecioActualAttribute()
    {
        $hoy = Carbon::today();
        
        $tarifaEspecial = $this->tipoHabitacion->tarifasDinamicas()
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();

        return $tarifaEspecial ? $tarifaEspecial->precio_modificado : $this->tipoHabitacion->precio_base;
    }
}