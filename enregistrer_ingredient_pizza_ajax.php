<?php
/*
Controleur pour Ajax:
    Enregistrer l'ingredient de la pizza et calculer le prix
Parametre: 
    $ingredient: l'ingredient de la pizza en get

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
    $ingredient = $_GET["ingredient"];
} else {
    $ingredient = 0;
}

// Traitement :
//1-J'enregistre l'ingredient de la pizza dans la table associee ingredients_choisis
$pizza->ajouterIngredient($ingredient);
//2- Avant de calculer le cout du supplement, je vérifie que la taille a bien été choisie et que la pizza a un prix
$taille = $pizza->get("taille");
$prixBase = $pizza->get("prix_base");
if($taille && is_numeric($prixBase)){
    //3- si oui, je calcule le supplement
    $supplement = $pizza->calculerSupplement();
    //4- je calcule le nouveau prix de la pizza
    $Prix = $prixBase + $supplement;
    //5- Je mets à jour l'objet
    $pizza->set("prix", $Prix);
    //6- je mets à jour la bdd
    $pizza->update();
}

// Afficher la page: 
ob_start();
include "templates/fragments/recap_pizza.php";
echo ob_get_clean();