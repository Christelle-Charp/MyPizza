<?php

// Initialisations communes à tous les controleurs 
// (à inclure en début de chaque controleur)


// mettre en place les messages d'erreur - Pensez à supprimer à la mise en production
ini_set('display_errors',1);
error_reporting(E_ALL);

// Initialiser / récupérer les infos de session
session_start();    // Permet de gerer les cookies, 
//récupère le tableau $_SESSION avec sa dernière valeur connue, surtout l'id de la pizza en cours de préparation

// Charger les librairies
include "library/bdd.php";
//include "library/session.php";

// Charger les différentes classes 
// - de modèle de données avec l'autochargement des classes
//On commence par créer la fonction
function autoLoadModele($class){
    //Role: chargement d'une classe correspondant à un objet appelé
    //Parametre: 
    //      $class: nom de la classe à charger
    //Retour: Néant

    $path = explode("\\", $class);

    $domain = $path[0];
    if ($domain != "modele") return;    // cette fonction ne gere que les classes contenues dans le dossier modele

    $dir = "modele/";
    for ($n = 1; $n < count($path) -1; $n++) {  //du second élément car n=1 et pas 0 à l'avant diernier car le dernier est le fichier de la classe
        $dir .=$path[$n] . "/";     //On ajoute le chemin du dossier

    }
    $dir .=$path[count($path) -1] . ".php";     //On ajoute le nom de la classe et l'extension du fichier 

    //On vérifie que le fichier du nom de la classe existe bien dans le dossier modele
    if(file_exists($dir)) {
        //on inclut alors le fichier
        include_once($dir);
    }
}

//- de la library
//On commence par créer la fonction
function autoLoadLibrary($class){
    //Role: chargement d'une classe correspondant à un objet appelé
    //Parametre: 
    //      $class: nom de la classe à charger
    //Retour: Néant

    $path = explode("\\", $class);

    $domain = $path[0];
    if ($domain != "library") return;    // cette fonction ne gere que les classes contenues dans le dossier library

    $dir = "library/";
    for ($n = 1; $n < count($path) -1; $n++) {  //du second élément car n=1 et pas 0 à l'avant diernier car le dernier est le fichier de la classe
        $dir .=$path[$n] . "/";     //On ajoute le chemin du dossier

    }
    $dir .=$path[count($path) -1] . ".php";     //On ajoute le nom de la classe et l'extension du fichier 

    //On vérifie que le fichier du nom de la classe existe bien dans le dossier library
    if(file_exists($dir)) {
        //on inclut alors le fichier
        include_once($dir);
    }
}

//On appelle les fonctions:
spl_autoload_register("autoLoadModele");
spl_autoload_register("autoLoadLibrary");

//include "modele/_model.php";
//include "modele/Option.php";
//include "modele/Pizza.php";

// Ouvrir la BDD dans la variable globale $bdd
global $bdd;
$bdd = new PDO("mysql:host=172.18.0.1;dbname=nom;charset=UTF8", "nom", "motdepasse");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING) ;  // Pensez à supprimer à la mise en production