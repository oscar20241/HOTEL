<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitacionMantenimiento extends Model
{
    use HasFactory;

    protected $table = 'habitacion_mantenimientos';

    protected $fillable = [
        'habitacion_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }
}
