<?php

namespace App\Controllers;


class HomeController 
{

    public function home(): void
    {
        readfile('./app/views/home.html');       
    }
}
