<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Base controller — injects authorization support into all derived controllers.
 */
abstract class Controller
{
    use AuthorizesRequests;
}
