<?php
/*
Classe décrivant l'objet Base du modèle conceptuel
*/

namespace modele;

class Base extends _api{
    //Décrire l'objet réel: attributs pour décrire l'objet
    protected static $urlAPI = "https://api.mywebecom.ovh/play/mypizza/bases";
    protected $attributs = ["nom", "description", "image"];
}