<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class AdminReservasController extends Controller
{
    // Si quieres una vista dedicada (puedes omitir y usar tu dashboard actual)
    public function view()
    {
        $habitaciones = Habitacion::orderBy('numero')->get(['id','numero']);
        $estados = ['pendiente','confirmada','activa','completada','cancelada'];

        return view('admin.reservas.index', compact('habitaciones','estados'));
    }

    // Listado para la tabla con filtros
    public function apiIndex(Request $request)
    {
        $q              = trim((string) $request->get('q'));
        $habitacionId   = $request->integer('habitacion_id');
        $estado         = $request->get('estado');
        $desde          = $request->date('desde'); // Y-m-d
        $hasta          = $request->date('hasta'); // Y-m-d

        $res = Reservacion::query()
            ->with(['habitacion:id,numero','user:id,name,email'])
            ->when($habitacionId, fn($qq) => $qq->where('habitacion_id',$habitacionId))
            ->when($estado, fn($qq) => $qq->where('estado',$estado))
            ->when($desde, fn($qq) => $qq->whereDate('fecha_entrada','>=',$desde))
            ->when($hasta, fn($qq) => $qq->whereDate('fecha_salida','<=',$hasta))
            ->when($q, function($qq) use ($q) {
                $qq->whereHas('user', fn($u)=>$u->where('name','like',"%$q%"))
                   ->orWhereHas('habitacion', fn($h)=>$h->where('numero','like',"%$q%"));
            })
            ->orderByDesc('created_at')
            ->limit(300) // protege la tabla
            ->get();

        $data = $res->map(function($r){
            return [
                'id'          => $r->id,
                'habitacion'  => $r->habitacion?->numero,
                'huesped'     => $r->user?->name,
                'check_in'    => $r->fecha_entrada->format('Y-m-d'),
                'check_out'   => $r->fecha_salida->format('Y-m-d'),
                'estado'      => $r->estado,
                'precio'      => number_format((float)$r->precio_total,2,'.',''),
            ];
        });

        return response()->json(['items'=>$data]);
    }

    // Eventos para FullCalendar (bloques por reserva)
    public function apiEvents(Request $request)
    {
        $habitacionId = $request->integer('habitacion_id');
        $estado       = $request->get('estado');
        $start        = $request->get('start'); // ISO de FullCalendar
        $end          = $request->get('end');

        $query = Reservacion::query()->with(['habitacion:id,numero','user:id,name']);

        if ($habitacionId) $query->where('habitacion_id',$habitacionId);
        if ($estado)       $query->where('estado',$estado);

        // rango sugerido por FullCalendar
        if ($start) $query->whereDate('fecha_salida','>', $start);
        if ($end)   $query->whereDate('fecha_entrada','<', $end);

        $colorByEstado = [
            'pendiente'  => '#f39c12',
            'confirmada' => '#0ea5e9',
            'activa'     => '#10b981',
            'completada' => '#6b7280',
            'cancelada'  => '#ef4444',
        ];

        $events = $query->get()->map(function($r) use ($colorByEstado){
            $title = 'Hab '.$r->habitacion?->numero.' – '.$r->user?->name;
            return [
                'id'    => (string)$r->id,
                'title' => $title,
                'start' => $r->fecha_entrada->toDateString(),
                'end'   => $r->fecha_salida->toDateString(), // FullCalendar usa end exclusivo
                'color' => $colorByEstado[$r->estado] ?? '#64748b',
                'extendedProps' => [
                    'estado'     => $r->estado,
                    'habitacion' => $r->habitacion?->numero,
                    'huesped'    => $r->user?->name,
                    'precio'     => (float)$r->precio_total,
                ],
            ];
        });

        return response()->json($events);
    }
}
