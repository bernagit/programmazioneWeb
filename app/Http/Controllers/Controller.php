<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function redirectToErrorPage($error)
    {
        // redirect to error page
        return view('error', ['error' => $error]);
    }
}
