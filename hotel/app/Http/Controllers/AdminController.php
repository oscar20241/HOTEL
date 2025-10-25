<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    public function empleados()
    {
        return view('admin.empleados');
    }
    
    public function reportes()
    {
        return view('admin.reportes');
    }
}