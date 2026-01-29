<?php
/*
Classe décrivant l'objet Pate du modèle conceptuel
*/

namespace modele;

class Pate extends _api{
    //Décrire l'objet réel: attributs pour décrire l'objet
    protected static $urlAPI = "https://api.christelle-charpinet.fr/mypizza/pate";
    protected $attributs = ["nom", "description"];
}