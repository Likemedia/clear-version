<?php

namespace App\Http\Controllers;

use App\Models\Form;

class TemporarController extends Controller
{
    public function index() {

        $form = Form::with('translation')->first();

        $json = $form->translation()->first()->code;

        return view('form', compact('json'));
    }
}