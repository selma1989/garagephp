<?php

namespace App\ConfigControllers;
use App\Models\Car;

/**
 * Gére la logique de la page d'accueil
 */

class HomeController extends BaseController{


    /**
     * Affiche la page d'accueil
     */

    public function index():void{
        $carModel = new Car();

        $this->render('home/index',[
            'title'=>'Accueil - Garage php',
            'car'=> $carModel->all()
        ]);
    }

}