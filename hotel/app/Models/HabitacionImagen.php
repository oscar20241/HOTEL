<?php
// app/Models/HabitacionImagen.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitacionImagen extends Model
{
    use HasFactory;

    protected $table = 'habitacion_imagenes';

    protected $fillable = [
        'habitacion_id',
        'ruta_imagen',
        'nombre_original',
        'es_principal',
        'orden'
    ];

    protected $casts = [
        'es_principal' => 'boolean'
    ];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }
}