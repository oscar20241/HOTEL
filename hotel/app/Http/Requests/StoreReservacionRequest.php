<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;
use App\Models\Habitacion;

class StoreReservacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'habitacion_id'  => ['required','exists:habitaciones,id'],
            'personas'       => ['required','integer','min:1'],
            'fecha_entrada'  => ['required','date','after_or_equal:today'],
            'fecha_salida'   => ['required','date','after:fecha_entrada'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            $habitacion = Habitacion::with('reservaciones')->find($this->habitacion_id);
            if (!$habitacion) {
                $v->errors()->add('habitacion_id', 'Habitación no encontrada.');
                return;
            }

            // ❌ En mantenimiento
            if ($habitacion->estado === 'mantenimiento') {
                $v->errors()->add('habitacion_id', 'La habitación está en mantenimiento.');
                return;
            }

            // ✅ Capacidad: personas ≤ capacidad
            $personas = (int) $this->personas;
            if ($personas > (int) $habitacion->capacidad) {
                $v->errors()->add('personas', "Máximo {$habitacion->capacidad} personas para esta habitación.");
            }

            // ✅ Fechas válidas y sin traslapes
            $in  = Carbon::parse($this->fecha_entrada)->startOfDay();
            $out = Carbon::parse($this->fecha_salida)->startOfDay();

            // Reglas de traslape (intervalos [in, out)):
            $solapa = $habitacion->reservaciones()
                ->whereIn('estado', ['pendiente','confirmada','activa'])
                ->where(function($q) use ($in, $out) {
                    $q->whereBetween('fecha_entrada', [$in, $out->copy()->subDay()])
                      ->orWhereBetween('fecha_salida', [$in->copy()->addDay(), $out])
                      ->orWhere(function($w) use ($in, $out) {
                          $w->where('fecha_entrada', '<=', $in)
                            ->where('fecha_salida', '>=', $out);
                      });
                })
                ->exists();

            if ($solapa) {
                $v->errors()->add('fecha_entrada', 'Las fechas seleccionadas no están disponibles.');
            }
        });
    }
}
