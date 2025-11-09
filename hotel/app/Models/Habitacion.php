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

    protected $fillable = [
        'numero',
        'tipo_habitacion_id',
        'estado',
        'capacidad',
        'caracteristicas',
        'amenidades'
    ];

    protected $casts = [
        'amenidades' => 'array'
    ];

    public function tipoHabitacion()
    {
        return $this->belongsTo(TipoHabitacion::class);
    }

    public function imagenes()
    {
        return $this->hasMany(HabitacionImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(HabitacionImagen::class)->where('es_principal', true);
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
        
        $tarifas = $this->tipoHabitacion->tarifasDinamicas()
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->get();

        $prioridades = [
            'especial' => 1,
            'alta' => 2,
            'baja' => 3,
        ];

        $tarifaEspecial = $tarifas
            ->sort(function ($tarifaA, $tarifaB) use ($prioridades) {
                $prioridadA = $prioridades[$tarifaA->tipo_temporada] ?? 4;
                $prioridadB = $prioridades[$tarifaB->tipo_temporada] ?? 4;

                if ($prioridadA !== $prioridadB) {
                    return $prioridadA <=> $prioridadB;
                }

                $inicioA = $tarifaA->fecha_inicio instanceof Carbon
                    ? $tarifaA->fecha_inicio
                    : Carbon::parse($tarifaA->fecha_inicio);
                $inicioB = $tarifaB->fecha_inicio instanceof Carbon
                    ? $tarifaB->fecha_inicio
                    : Carbon::parse($tarifaB->fecha_inicio);

                if ($inicioA->equalTo($inicioB)) {
                    return 0;
                }

                return $inicioA->greaterThan($inicioB) ? -1 : 1;
            })
            ->first();

        return $tarifaEspecial ? (float) $tarifaEspecial->precio_modificado : (float) $this->tipoHabitacion->precio_base;
    }
}