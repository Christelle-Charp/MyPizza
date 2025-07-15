<?php
/*
Controleur pour Ajax:
    Supprimer l'ingredient de la pizza
    Calculer le prix de la pizza
Parametre: 
    $ingredient: l'id de l'ingredient déjà choisi à supprimer en get

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
//Je supprime l'ingredient de la table associée
$suppression = $pizza->supprimerIngredient($ingredient);
//Si la suppression est ok et que la taille de la pizza est remplie
$taille = $pizza->get("taille");
$prixBase = $pizza->get("prix_base");
if($suppression && $taille && is_numeric($prixBase)){
    //1- Je recalcule le supplement
    $supplement = $pizza->calculerSupplement();
    //2- je calcule le nouveau prix de la pizza
    $newPrix = $prixBase + $supplement;
    //3- Je mets à jour l'objet
    $pizza->set("prix", $newPrix);
    //5- je mets à jour la bdd
    $pizza->update();
}

// Afficher la page: 
include "templates/fragments/recap_pizza.php";