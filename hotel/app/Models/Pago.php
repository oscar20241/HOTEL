<?php
// app/Models/Pago.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    
    protected $table = 'pagos';

    protected $fillable = [
        'reservacion_id',
        'monto',
        'metodo_pago',
        'estado',
        'referencia',
        'fecha_pago'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function reservacion()
    {
        return $this->belongsTo(Reservacion::class);
    }

    public function marcarComoCompletado()
    {
        $this->update([
            'estado' => 'completado',
            'fecha_pago' => now()
        ]);
    }

    public function esExitoso()
    {
        return $this->estado === 'completado';
    }
}