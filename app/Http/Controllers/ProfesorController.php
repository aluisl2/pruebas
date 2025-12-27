<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfesorController extends Controller
{
    public function view()
    {
        return Inertia::render('Miembros/ProfesorMiembros');
    }

    public function index()
    {
        return Inertia::render('Retos/ProfesorRetos');
    }
}
