<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('welcome', [
            'title' => 'Hello from HomeController',
            'message' => 'Rendered via controller method.'
        ]);
    }
}
