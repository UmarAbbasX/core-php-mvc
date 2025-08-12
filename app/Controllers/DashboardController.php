<?php

namespace App\Controllers;

use Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        // Pass data to the view if needed
        $this->view('dashboard');
    }
}
