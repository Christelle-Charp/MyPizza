<?php
/*
Controleur pour Ajax:
    Enregistrer la taille de la pizza et calculer le prix
Parametre: 
    $taille: la taille de la pizza en get

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
if(isset($_GET["taille"])){
    $taille = $_GET["taille"];
} else {
    $taille = 0;
}

// Traitement :
//J'enregistre la taille de la pizza dans la pizza en cours dans la bdd
//1- je mets à jour l'objet pizza
$pizza->set("taille", $taille);
//2- je récupere le prix:
$prix = Taille::factory($taille)->get("prix");
//3- je le mets à jour dans l'objet
$pizza->set("prix", $prix);
$pizza->set("prix_base", $prix);    //Je stock le prix de base de la pizza.
//4- je mets à jour la bdd
$pizza->update();


// Afficher la page: 
include "templates/fragments/recap_pizza.php";