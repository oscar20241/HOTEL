<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('Gerente');
    }
    
    public function empleados()
    {
        return view('Gerente');
    }
    
    public function reportes()
    {
        return view('Gerente');
    }
}