<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecepcionistaController extends Controller
{
    public function dashboard()
    {
        return view('recepcionista.dashboard');
    }
    
    public function reservaciones()
    {
        return view('recepcionista.reservaciones');
    }
    
    public function checkin()
    {
        return view('recepcionista.checkin');
    }
}