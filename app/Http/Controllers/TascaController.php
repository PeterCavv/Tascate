<?php

namespace App\Http\Controllers;

use App\Models\Tasca;
use Inertia\Inertia;

class TascaController extends Controller
{
    public function index()
    {
        $tascas = Tasca::with('user', 'reservations', 'reviews.customer.user')->get();

        return Inertia::render('Tascas/TascasIndex', [
            'tascas' => $tascas,
        ]);
    }

    public function show(Tasca $tasca)
    {
        return Inertia::render('Tascas/TascaShow', [
            'tasca' => $tasca->load('user', 'reservations', 'reviews.customer.user'),
        ]);
    }
}
