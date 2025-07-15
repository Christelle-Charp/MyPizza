<?php
/*
Controleur:
    Extraire liste ingrédients, pates, tailles, bases
    Préparer affichage de la page de creation de la pizza
Parametre: 
    neant

*/

/* Initialisations 
    Tout est regroupé dans le init:     
*/

use library\GestionSessionPizza;
use modele\Ingredient;
use modele\Base;
use modele\Pizza;
use modele\Pate;
use modele\Taille;

include "library/init.php";

// Vérification si une pizza est en cours:
// S'il n'y a pas de pizza en cours, je crée une pizza, je récupère l'id de la pizza et je connecte la session:
$pizza = GestionSessionPizza :: initierOuCharger();

// Récupération des paramètrs : 
//Neant

// Traitement :
//Extraire liste ingredients
$listIngredient = Ingredient::listAll();
//Extraire liste taille
$listTaille = Taille::listAll();
//Extraire liste Base
$listBase = Base::listAll();
//Extraire liste pate
$listPate = Pate::listAll();

// Afficher la page: 
include "templates/pages/creation_pizza.php";