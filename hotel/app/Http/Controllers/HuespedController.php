<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HuespedController extends Controller
{
    public function dashboard()
    {
        return view('huesped.dashboard');
    }
    
    public function reservar()
    {
        return view('huesped.reservar');
    }
    
    public function misReservaciones()
    {
        return view('huesped.mis-reservaciones');
    }
}