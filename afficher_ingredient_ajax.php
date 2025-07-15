<?php
/*
Controleur pour Ajax:
    Récupérer les infos de l'ingredient
Parametre: 
    $ingredient: l'id de l'ingredient

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
if(isset($_GET["ingredient"])){
    $idIngredient = $_GET["ingredient"];
} else {
    $idIngredient = 0;
}

// Traitement :
$ingredient = Ingredient::factory($idIngredient);


// Afficher la page: 
include "templates/fragments/popup_option.php";