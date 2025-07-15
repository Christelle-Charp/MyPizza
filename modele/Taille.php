<?php
/*
Classe décrivant l'objet Taille du modèle conceptuel
*/

namespace modele;

class Taille extends _api{
    //Décrire l'objet réel: attributs pour décrire l'objet
    protected static $urlAPI = "https://api.mywebecom.ovh/play/mypizza/tailles";
    protected static $urlAPIPrix = "https://api.mywebecom.ovh/play/mypizza/prix";
    protected $attributs = ["nom", "description", "prix", "supplement"];
    protected static $prix = null;

    //Méthode surchargée

    //Surcharge de LoadFromTab() pour y ajouter le prix
    function loadFromTab($tab){
        //Role: Ajouter le prix à la methode loadFromTab car le prix est donné par une 2eme api
        //Parametre:
        //      $tab: les données extraites de l'api
        //Retour:true si ok sinon false

        //j'indique la methode que je veux surcharger (qui ne gere pas l'attribut id)
        parent::loadFromTab($tab);

        //j'ajoute l'id
        if(isset($tab[$this->idChamp])){
            $this->id = $tab[$this->idChamp];
            $this->{$this->idChamp} = $tab[$this->idChamp];

        }

        //Je charge le prix qui vient d'une autre api
        $id = $tab[$this->idChamp] ?? null;
        if ($id && static::$prix===null){
            static::$prix = $this->chargerPrix();
        }

        if(isset(static::$prix[$id])){
            $this->set("prix", static::$prix[$id]);
        } else {
            $this->set("prix", "prix inconnu");
        }

        //Je charge le supplement
        if(isset(static::$prix["supplement"])){
            $this->set("supplement", static::$prix["supplement"]);
        } else {
            $this->set("supplement", "prix inconnu");
        }
    }

    function chargerPrix(){
        //Role: Récupérer les informations de l'api de prix
        //Parametre:
        //      Neant
        //Retour: un tableau
        $reponse = $this->callAPI(static::$urlAPIPrix);
        return is_array($reponse) ? $reponse : [];
    }
}