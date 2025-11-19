<?php

namespace App\Http\Controllers;
use App\Models\TipoHabitacion;
use App\Models\Habitacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicHabitacionController extends Controller
{
    /**
     * Display a listing of the rooms for guests.
     */
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->esAdministrador() || $user->esGerente()) {
                return redirect()->route('gerente.dashboard');
            }
        }

        $tiposHabitacion = TipoHabitacion::with([
                'habitaciones' => function ($query) {
                    $query->with(['imagenPrincipal', 'imagenes'])->orderBy('numero');
                },
                'tarifasDinamicas',
            ])
            ->orderBy('precio_base')
            ->get();

        return view('public.habitaciones.index', [
            'tiposHabitacion' => $tiposHabitacion,
        ]);
    }

    /**
     * Display the specified room details.
     */
    public function show(Habitacion $habitacion)
    {
        if (auth()->check() && (auth()->user()->esAdministrador() || auth()->user()->esGerente())) {
            return redirect()->route('gerente.dashboard');
        }

        $habitacion->load([
            'tipoHabitacion.habitaciones' => function ($query) {
                $query->select('id', 'tipo_habitacion_id', 'estado', 'capacidad', 'numero');
            },
            'imagenes',
        ]);

        return view('public.habitaciones.show', [
            'habitacion' => $habitacion,
        ]);
    }

    /**
     * Return JSON availability blocks for the requested room.
     */
    public function disponibilidad(Request $request, Habitacion $habitacion)
    {
        $bloques = [];

        if ($habitacion->estaEnMantenimiento()) {
            $bloques[] = [
                'from' => now()->toDateString(),
                'to' => now()->addDays(180)->toDateString(),
                'estado' => 'mantenimiento',
            ];
        }

        $reservacionIgnorada = $request->query('exclude_reservacion');

        $reservas = $habitacion->reservaciones()
            ->whereIn('estado', ['pendiente', 'confirmada', 'activa'])
            ->when($reservacionIgnorada, function ($query) use ($reservacionIgnorada) {
                $query->where('id', '!=', $reservacionIgnorada);
            })
            ->orderBy('fecha_entrada')
            ->get(['fecha_entrada', 'fecha_salida', 'estado']);

        foreach ($reservas as $reserva) {
            $noches = $reserva->fecha_entrada->diffInDays($reserva->fecha_salida);

            if ($noches < 1) {
                continue;
            }

            $bloques[] = [
                'from' => $reserva->fecha_entrada->toDateString(),
                'to' => $reserva->fecha_salida->copy()->subDay()->toDateString(),
                'estado' => 'ocupada',
            ];
        }

        return response()->json([
            'capacidad' => (int) $habitacion->capacidad,
            'bloques' => $bloques,
        ]);
    }

    public function disponibilidadPorTipo(Request $request, TipoHabitacion $tipoHabitacion)
    {
        $inicio = Carbon::today();
        $fin = (clone $inicio)->addDays(180);

        $tipoHabitacion->load(['habitaciones' => function ($query) use ($fin, $inicio) {
            $query->with(['reservaciones' => function ($reservaQuery) use ($fin, $inicio) {
                $reservaQuery->whereIn('estado', ['pendiente', 'confirmada', 'activa'])
                    ->where('fecha_entrada', '<', $fin->copy()->addDay())
                    ->where('fecha_salida', '>', $inicio);
            }]);
        }]);

        $habitaciones = $tipoHabitacion->habitaciones;
        $operativas = $habitaciones->filter->estaOperativa();

        $bloques = [];
        $estadoActual = null;
        $inicioBloque = null;

        $fecha = $inicio->copy();
        while ($fecha->lte($fin)) {
            $estadoDia = 'disponible';

            if ($operativas->isEmpty()) {
                $estadoDia = 'mantenimiento';
            } else {
                $hayDisponible = false;

                foreach ($operativas as $habitacion) {
                    $ocupada = $habitacion->reservaciones
                        ->contains(fn ($reserva) => $fecha->gte($reserva->fecha_entrada) && $fecha->lt($reserva->fecha_salida));

                    if (!$ocupada) {
                        $hayDisponible = true;
                        break;
                    }
                }

                if (!$hayDisponible) {
                    $estadoDia = 'ocupada';
                }
            }

            if ($estadoDia === 'disponible') {
                if ($estadoActual !== null) {
                    $bloques[] = [
                        'from' => $inicioBloque->toDateString(),
                        'to' => $fecha->copy()->subDay()->toDateString(),
                        'estado' => $estadoActual,
                    ];
                    $estadoActual = null;
                    $inicioBloque = null;
                }
            } else {
                if ($estadoActual !== $estadoDia) {
                    if ($estadoActual !== null) {
                        $bloques[] = [
                            'from' => $inicioBloque->toDateString(),
                            'to' => $fecha->copy()->subDay()->toDateString(),
                            'estado' => $estadoActual,
                        ];
                    }

                    $estadoActual = $estadoDia;
                    $inicioBloque = $fecha->copy();
                }
            }

            $fecha->addDay();
        }

        if ($estadoActual !== null) {
            $bloques[] = [
                'from' => $inicioBloque->toDateString(),
                'to' => $fin->toDateString(),
                'estado' => $estadoActual,
            ];
        }

        return response()->json([
            'capacidad' => (int) $tipoHabitacion->capacidad,
            'bloques' => $bloques,
        ]);
    }
}
