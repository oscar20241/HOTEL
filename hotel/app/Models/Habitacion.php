<?php
// app/Models/Habitacion.php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function estadoNormalizado(): string
    {
        return Str::lower($this->estado ?? '');
    }

    public function estadoEs(string $estado): bool
    {
        return $this->estadoNormalizado() === Str::lower($estado);
    }

    public function estaEnMantenimiento(): bool
    {
        return $this->estadoEs('mantenimiento');
    }

    public function estaOperativa(): bool
    {
        return !$this->estaEnMantenimiento();
    }

    public function estaDisponible($fechaEntrada, $fechaSalida, ?int $reservacionIgnorarId = null)
    {
        // No disponible si está en mantenimiento
        if ($this->estaEnMantenimiento()) {
            return false;
        }

        // Verificar si hay reservaciones conflictivas (se permite excluir una reservación existente)
        return !$this->reservaciones()
            ->whereIn('estado', ['confirmada', 'activa', 'pendiente'])
            ->when($reservacionIgnorarId, function ($query) use ($reservacionIgnorarId) {
                $query->where('id', '!=', $reservacionIgnorarId);
            })
            ->where(function ($query) use ($fechaEntrada, $fechaSalida) {
                $query->where('fecha_entrada', '<', $fechaSalida)
                    ->where('fecha_salida', '>', $fechaEntrada);
            })
            ->exists();
    }

    public function getPrecioActualAttribute()
    {
        $hoy = Carbon::today();
        
        $tarifas = $this->tipoHabitacion->tarifasDinamicas()
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->orderByRaw("FIELD(tipo_temporada, 'especial', 'alta', 'baja')")
            ->orderByDesc('fecha_inicio')
            ->first();

        return $tarifas 
    ? (float) $tarifas->precio_modificado 
    : (float) $this->tipoHabitacion->precio_base;

    }
}