<?php
/*
Classe décrivant l'objet Pate du modèle conceptuel
*/

namespace modele;

class Pate extends _api{
    //Décrire l'objet réel: attributs pour décrire l'objet
    protected static $urlAPI = "https://api.mywebecom.ovh/play/mypizza/pates";
    protected $attributs = ["nom", "description"];
}