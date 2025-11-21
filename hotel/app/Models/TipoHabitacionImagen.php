<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacionImagen extends Model
{
    use HasFactory;

    protected $table = 'tipo_habitacion_imagenes';

    protected $fillable = [
        'tipo_habitacion_id',
        'ruta_imagen',
        'nombre_original',
        'es_principal',
        'orden',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    public function tipoHabitacion()
    {
        return $this->belongsTo(TipoHabitacion::class);
    }
}
