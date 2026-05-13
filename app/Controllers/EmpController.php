<?php

namespace App\Controllers;

class EmpController extends BaseController
{
    public function index(): string
    {
        return view('employe/index');
    }

    public function formconge(): string
    {
        return view('employe/create');
    }

    public function demadeconge(): string
    {
        return view('employe/deamandeconge');
    }
}
