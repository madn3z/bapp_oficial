<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApplicationController extends Controller
{
    public function __invoke()
    {
        //dd(Auth::user());
        return view('application');
    }
}
