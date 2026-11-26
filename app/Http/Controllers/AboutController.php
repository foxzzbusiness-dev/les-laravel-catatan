<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index () {
    $judul = 'Tentang Kami';
    $deskripsi = 'Website ini dibuat untuk belajar laravel';
        return view('about', compact('judul', 'deskripsi'));
    }
}
