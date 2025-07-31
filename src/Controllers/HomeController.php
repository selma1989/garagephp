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

         // On rend la vue 'home/index' et on lui passe le titre et la liste des voitures.
        $this->render('home/index',[
            'title'=>'Accueil - Garage php',
            'car'=> $carModel->all()
        ]);
    }

}