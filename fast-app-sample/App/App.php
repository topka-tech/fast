<?php

namespace App;

class App extends \Fast\FastApp
{
    protected $endPoints = [
        \App\EndPoints\UserEndPoint::class,
    ];

}