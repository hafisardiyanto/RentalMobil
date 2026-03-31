<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        // Get 6 featured cars available
        $featuredCars = Car::where('is_available', true)->take(6)->get();
        return view('welcome', compact('featuredCars'));
    }
}
