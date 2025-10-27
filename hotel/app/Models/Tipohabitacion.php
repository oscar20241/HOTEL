<?php
// app/Models/TipoHabitacion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    use HasFactory;
<<<<<<< HEAD
=======
    protected $table = 'tipos_habitacion';
>>>>>>> d3a78b76a17d842439eea092664b7c7eb0f5309e

    protected $fillable = ['nombre', 'descripcion', 'capacidad', 'precio_base'];

    protected $casts = [
        'precio_base' => 'decimal:2',
    ];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class);
    }

    public function tarifasDinamicas()
    {
        return $this->hasMany(TarifaDinamica::class);
    }
}