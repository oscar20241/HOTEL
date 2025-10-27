<?php
// app/Models/Reservacion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservacion extends Model
{
    use HasFactory;
    
    protected $table = 'reservaciones';

    protected $fillable = [
        'codigo_reserva',
        'user_id',
        'habitacion_id',
        'fecha_entrada',
        'fecha_salida',
        'numero_huespedes',
        'estado',
        'precio_total',
        'notas'
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_salida' => 'date',
        'precio_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservacion) {
            if (empty($reservacion->codigo_reserva)) {
                $reservacion->codigo_reserva = 'RES' . strtoupper(Str::random(8));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function getNochesAttribute()
    {
        return $this->fecha_entrada->diffInDays($this->fecha_salida);
    }

    public function puedeModificarse()
    {
        return in_array($this->estado, ['pendiente', 'confirmada']);
    }

    public function puedeCancelarse()
    {
        return in_array($this->estado, ['pendiente', 'confirmada']);
    }
}