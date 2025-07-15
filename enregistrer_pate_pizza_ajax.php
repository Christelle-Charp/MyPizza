<?php
/*
Controleur pour Ajax:
    Enregistrer la pate de la pizza
Parametre: 
    $pate: la pate de la pizza en get

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
if(isset($_GET["pate"])){
    $pate = $_GET["pate"];
} else {
    $pate = 0;
}

// Traitement :
//J'enregistre la pate de la pizza dans la pizza en cours dans la bdd
//1- je mets à jour l'objet pizza
$pizza->set("pate", $pate);
//2- je mets à jour la bdd
$pizza->update();


// Afficher la page: 
include "templates/fragments/recap_pizza.php";