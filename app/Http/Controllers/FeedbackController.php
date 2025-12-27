<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function create()
    {
        return Inertia::render('Feedback/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comentarios' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'usuario_id' => auth()->id(),
            'comentarios' => $validated['comentarios'],
        ]);

        return back()->with('success', '¡Gracias por tus comentarios! Son muy valiosos para nosotros.');
    }
}