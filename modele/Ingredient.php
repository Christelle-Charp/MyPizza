<?php
/*
Classe décrivant l'objet Ingredient du modèle conceptuel
*/

namespace modele;

class Ingredient extends _api{
    //Décrire l'objet réel: attributs pour décrire l'objet
    protected static $urlAPI = "https://api.christelle-charpinet.fr/mypizza/ingredients";
    protected $attributs = ["nom", "description", "image"];
}