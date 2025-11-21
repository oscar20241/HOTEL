<?php
// app/Models/TipoHabitacion.php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    use HasFactory;

    protected $table = 'tipos_habitacion';

    protected $fillable = ['nombre', 'descripcion', 'capacidad', 'precio_base'];

    protected $casts = [
        'precio_base' => 'decimal:2',
    ];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class);
    }

    public function imagenes()
    {
        return $this->hasMany(TipoHabitacionImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(TipoHabitacionImagen::class)->where('es_principal', true);
    }

    public function tarifasDinamicas()
    {
        return $this->hasMany(TarifaDinamica::class);
    }

    public function getPrecioActualAttribute(): float
    {
        $hoy = Carbon::today();

        $tarifa = $this->tarifasDinamicas()
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->orderByRaw("FIELD(tipo_temporada, 'especial', 'alta', 'baja')")
            ->orderByDesc('fecha_inicio')
            ->first();

        return $tarifa
            ? (float) $tarifa->precio_modificado
            : (float) $this->precio_base;
    }
}