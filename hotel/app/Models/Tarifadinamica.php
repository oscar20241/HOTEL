<?php
// app/Models/TarifaDinamica.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifaDinamica extends Model
{
    use HasFactory;
    protected $table = 'tarifas_dinamicas';

    protected $fillable = [
        'tipo_habitacion_id',
        'fecha_inicio',
        'fecha_fin',
        'precio_modificado',
        'tipo_temporada',
        'descripcion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'precio_modificado' => 'decimal:2',
    ];

    public function tipoHabitacion()
    {
        return $this->belongsTo(TipoHabitacion::class);
    }
}