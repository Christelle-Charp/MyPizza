<?php

//Classe permettant de gérer la session pizza

/*
Ce dont j'ai besoin:
- A chaque ouverture de page, je dois créer une session qui va contenir les informations sur la pizza que le consommateur est en train de créer au moins qu'il y est déja une pizza en cours
- Le consommateur n'a pas besoin d'etre connecté
- A quel moment, je lance la session?
    - Dès que le consommateur arrive sur la page, sa pizza est crée et je dois donc remplir le tableau $_SESSION["pizza"] avec l'id de la pizza.
- Quand utiliser cette session:
    - Quand le consommateur revient sur la page et qu'il a déjà une pizza est cours
    - Au moment ou il crée sa pizza et tout le long des choix jusqu'à la validation de la commande.
- Quand déconnecter la session et vider les infos du tableau:
    - A la finalisation de la commande.
    - Apres 24h d'inactivité
- Quand je déconnecte la pizza, je supprime la pizza et ses ingredients pour ne pas surcharger la bdd
- Qu'est ce que je mets dans la session:
    - l'id de la pizza
    - son statut si active ou non
    - la date de la dernière action
*/

namespace library;

use modele\Pizza;
use modele\Ingredient;
use modele\Taille;
use modele\Pate;
use modele\Base;

class GestionSessionPizza {

    /*
    Toutes les fonctions de cette classe sont en mode static car je n'instancie pas d'objet GestionSessionPizza et je ne modifie donc pas d'attribut
    de cet objet mais seulement la variable global $_SESSION 
    */

    public static function initier($id){
    //Role: Déclarer qu'une pizza est en cours - Fonction d'activation de la connexion
    //Parametre: 
    //      $id: id de la pizza en cours de creation
    //Retour: true si réussi, sinon false

    $_SESSION["pizza"] = $id;
    $_SESSION["pizza_active"] = true;
    $_SESSION["pizza_derniere_action"]=time();
    return true;
    }

    public static function terminer(){
    //Role: Terminer la session de la pizza qui est en cours
    //Parametre: 
    //      neant
    //Retour: true si réussi, sinon false

    /* A ne pas utiliser, car pas de disction entre une pizza commandée ou une pizza "abandonnée"
    // Quand je termine la session de la pizza, je nettoye la BDD de la pizza et de ses ingredients 
    if(!empty($_SESSION["pizza"])) {
        $pizza = Pizza::factory($_SESSION["pizza"]);
        $pizza->nettoyagePizza();
    }*/
    
    //Je réinitialise le tableau $_SESSION des infos concernant la pizza.
    $_SESSION["pizza"] = 0;
    $_SESSION["pizza_active"] = false;
    $_SESSION["pizza_derniere_action"]=null;

    //Je vide les tableaux $loadedObjects
    Pizza::resetCache();
    Ingredient::resetCache();
    Taille::resetCache();
    Pate::resetCache();
    Base::resetCache();

    return true;
    }

    public static function estActive(){
    //Role: indiquer qu'une pizza est en cours de création et prendre en compte le délai depuis la derniere action
    //Parametre: Néant
    //Retour: true si une pizza est encours depuis moins de 24h, sinon false

    //1ere étape: je vérifie si une pizza a été initiée
    if(!empty($_SESSION["pizza"]) && $_SESSION["pizza_active"]){
        //2eme étape, je vérifie si le délai entre maintenant et la pizza_derniere_action est inferieur à 24h
        if(time() - $_SESSION["pizza_derniere_action"] < 86400){
            //3eme étape, je mets à jour $_SESSION["pizza_derniere_action"]
            $_SESSION["pizza_derniere_action"] = time();
            //4eme étape, je retourne true
            return true;
        } else {
            //Si le délai est supérieur à 24h, je termine la session de la pizza
            self :: terminer(); //self évite de réécrire le nom de la classe à appeller car je suis dedans. Et comme pas d'héritage dans cette classe,
            // pas besoin d'utiliser static:: - self:: suffit
            return false;
        }
    } 
    return false;
    }

    public static function initierOuCharger(){
    //Role: Vérifier si une pizza est en cours, si oui chargé l'objet sinon créer la pizza et l'initier.
    //Parametre
    //      Neant
    //Retour: objet pizza chargé avec les infos de la session sinon un objet pizza vide
    
    //Je vérifie s'il y a une pizza en cours
    if(!self::estActive()){
        //S'il n'y en a pas, je crée un objet pizza vide, je l'enregistre dans la bdd et je récupére son id
        $pizza = new Pizza();
        $id = $pizza->insert();
        if(!empty($id)){
            // Si je récupérer bien l'id, j'initie la session
            self :: initier($id);
        }
    }
    //S'il y a une pizza en cours, je charge l'objet:
    return self :: charger();
    }

    public static function charger(){
    //Role: Charger l'objet pizza
    //Parametre:
    //Retour l'objet pizza chargé avec les infos de la pizza en cours

    if(self :: estActive()){
        return new Pizza ($_SESSION["pizza"]);
    } else {
        return new Pizza();
    }
    }
}