<?php

// Classe mere qui va servir de base aux autres classes chargées par une API
// Cette classe se contruit de manière généraliste et abstraite pour pouvoir etre aplliquée aux classes "enfants"

//Je déclare l'espace de nom de ma classe
namespace modele;

class _api{
    //Décrire l'objet réel: attributs pour décrire l'objet mais sans les nommer->ce fait dans la classe qui utilisera la cette classe comme "mère"
    protected static $urlAPI = "";          //URL de l'API à définir dans chaque classe fille
    protected $attributs = [];              // liste simple des noms des attributs sans l'id

    //Ajout des infos du modéle physique:
    protected $valeurs =[];     //stockage des valeurs des attributs (quand remplis) [ "champ1" = "valeur1", "champ2" => "valeur2" ]
    protected $idChamp = "id";  //Par défaut si pas de personnalisation dans la classe "fille"
    protected $id = 0;          //Modele appartenant au physique, l'id de l'objet
    static public $loadedObjects;  // Tableau de tableau stockant les objets déjà chargés
                        // Le premier niveau est indexé par le nom d'objet du MCD, le second par l'id de l'objet

    static function resetCache(){
        //Role: Effacer le contenu de $loadedObjects généré par la fabrique
        //Parametre: Néant
        //Retour: true si sinon false
        self::$loadedObjects = [];
        return true;
    }

    function __construct($id = null) {
        //Role: constructeur (appelé automatiquement au "new"): charge une ligne de la bdd si on précise un id
        //Parametre:
        //      $id: facultatif car dans la parenthèse il y a $id=null. id de la ligne de la bdd à charger dans l'objet qu'on crée
        if(! is_null($id)) {
            $this->loadFromId($id);
        }
    }

    function __get($nom){
        //Role: Fonction magagique appelé lorqu'on veut utiliser $objet->numfmt_get_attribut
        //Parametre:
        //      $nom: nom de l'attribut à récupérer
        //Retour: La même chose que la methode get($nom)

        return $this->get($nom);
    }

    function is() {
        // Rôle : vérifier si l'objet est chargé
        //parametre: néant
        //Retour: true s'il est chargé sinon false

        return !empty($this->id);

    }

    function id() {
        // Rôle : récupérer l'id de l'objet
        //Paramètre: néant
        //Retour: valeur de l'id ou sinon 0

        if(isset($this->id)){
            return $this->id;
        } else {
            return 0;
        }
    }

    function get($nom) {
        //Role: getter, récupérer la valeur d'un champ donné
        //Parametre:
        //      $nom: nom du champ à récupérer
        //Retour: valeur du champ ou valeur par défaut (chaine vide "")

        //Je vérifie si $nom fait partie des attributs dans le tableau listant le nom des attributs: $this->attributs
        if(! in_array($nom, $this->attributs)){
            return "";
        }

        //est ce qu'il a une valeur: il y a une valeur dans le tableau $this->valeurs (le tableau se charge au moment du loadFromId)
        if (isset($this->valeurs[$nom])) {
            //on retourne la valeur
            return $this->valeurs[$nom];
        } else {
            return "";
        }
    }

    function set($nom, $valeur) {
        //Role: setter, donne ou modifie la valeur d'un champ
        //Parametres:
        //      $nom: nom de l'attribut concerné
        //      $valeur: nouvelle valeur
        //retour: true si ok sinon false

        //Je vérifie si $nom fait partie des attributs dans le tableau listant le nom des attributs: $this->attributs
        if(! in_array($nom, $this->attributs)){
            return "";
        }

        //Si l'attribut existe, je remplace sa valeur par $valeur
        $this->valeurs[$nom] = $valeur;
        return true;
    }

    // Factory
    static function factory($id, $objectName = null){
        //Role: récupérer un objet du MCD chargé (s'il existe)
        //Parametres:
        //      $id: valeur de la clé primaire de chaque objet
        //      $objectName: (falcultatif car = null) nom de l'objet à charger
        //Retour: l'objet de la classe correspondant, chargé s'il existe depuis l'api

        //Si aucune classe précisée, on retourne un objet appartenant à la classe sur laquelle la methode est appelée
        if(empty($objectName)){
            $objectName = static :: class;
        }

        //Si $objectName n'existe pas, je retourne le model _api
        if(!class_exists($objectName)){
            return new _api();
        }

        //Si aucun objet de cette classe n'est dans le tableau, on crée l'entrée dans le tableau $loadedObjects
        if(!isset(self::$loadedObjects[$objectName])){
            self::$loadedObjects[$objectName] = [];
        }

        //Si un objet avec $id existe, on le retourne en recuperant les infos du tableau $loadedObjects
        if(isset(self::$loadedObjects[$objectName][$id])){
            return self::$loadedObjects[$objectName][$id];
        }

        //Si aucun objet avec cet id n'existe, on le crée, on le charge, on l'enregistre dans $loadedObjects et on le retourne
        self::$loadedObjects[$objectName][$id] = new $objectName($id);
        return self::$loadedObjects[$objectName][$id];
    }

    function callAPI($url){
        //Role: Récupérer les informations d'une API
        //Parametre:
        //      $url: url de l'API à appeler
        //Retour: la réponse décodée de l'API

        //Initialisatin de l'api:
        $api = curl_init($url);

        //On demande les données envoyées par l'API
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_TIMEOUT, 10); // Timeout de sécurité
        curl_setopt($api, CURLOPT_SSL_VERIFYPEER, false); // à éviter en prod si possible
        curl_setopt($api, CURLOPT_FOLLOWLOCATION, true);    //Si l'adresse est redirigée


        //On soumet la requete et on récupère le résultat (false en cas d'echec)
        $reponse = curl_exec($api);
        //On teste la réponse
        if ($reponse === false) {
            // erreur de la réponse
            $message = curl_error($api);
            curl_close($api);
            error_log("Erreur réseau lors de l’appel à $url : $message");
            return null;
        }

        curl_close($api);

        // Tentative de décodage JSON
        $data = json_decode($reponse, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erreur JSON à $url : " . json_last_error_msg());
            return null;
        }

        return $data;

    }

    static function listAll() {
        //Role: récupérer de l'API la liste de tous les objets et les rentrer dans $loadedObjects
        //Parametre:
        //      Neant
        //Retour: un tableau d'objets

        //Je récupere l'url qui est indiquée dans la classe fille
        $url = static :: $urlAPI;
        //J'instancie un objet de la classe en cours
        $typeObjet = new static();
        //Je récupere les données de l'API
        $donnees = $typeObjet->callAPI($url);
        if (!is_array($donnees)) {
            error_log("API vide ou invalide à $url");
            return [];
        }
        //Je transforme les données en tableau d'objet
        $list = $typeObjet->tab2TabObjects($donnees);
        //Je remplis le tableau $loadedObjects
        $objectName = static::class;
        if(!isset(self::$loadedObjects[$objectName])){
            self::$loadedObjects[$objectName] = [];
        }
        foreach($list as $id=>$objet){
            self::$loadedObjects[$objectName][$id] = $objet;
        }
        //Je gere le retour:
        return $list;

    }

    function loadFromId($id){
        //Role: charger un objet depuis les données récupérées de l'API
        //Parametre:
        //      $id: id à chercher dans les données de l'API
        //Retour: true si on a réussi sinon false

        //Cette methode va donc remplir le tableau $this->valeurs

        //Je récupère les données de l'APi sous forme de tableau d'objet
        $list = static :: listAll();
        //Je vérifie que l'id cherché est bien dans $list
        if(isset($list[$id])){
            //Si oui, je dis que l'objet que je cherche est celui de la liste avec le meme id
            $objetCherche = $list[$id];
            //J'indique que le tableau valeurs de l'objet que je dois charger est celui contenu avec l'id correspondant
            $this->valeurs = $objetCherche->valeurs;
            //Je rempli l'id de mon nouvel objet
            $this->id = $id;
            return true;
        }
        return false;
    }

    function loadFromTab($tab) {
        //Role: charger les valeurs des attributs à partir des données dans un tableau indexé par les noms de champ sauf l'id
        //Parametres:
        //      $tab: tableau indexé, dont les index sont des attributs (du modele physique)
        //Retour: true si ok sinon false

        //Pour chaque attribut de l'objet,
        // si il est dans le tableau, on charge sa valeur
        foreach($this->attributs as $nomChamp) {
            if (isset($tab[$nomChamp])) {       //Si on a une valeur dans la tableau $tab:
                //On affecte la valeur au champ: on se sert du setter  $this->set(nom du champ, valeur du champ)
                $this->set($nomChamp, $tab["$nomChamp"]);
            }
        }
        return true;
    }

    function tab2TabObjects($tab) {
        //Role: Transformer, à partir d'un tableau simple de tableaux indexés des valeurs des champs (type de tableau récupéré de l api)
        // en un tableau de la classe courante
        // Parametre:
        //      $tab: tableau à transformer
        //Retour: tableau d'objets de la classe courante, indexé par l'id

        //On part d'un tableau de résultat vide:
        $result = [];
        //Pour chaque ligne de $tab
        foreach ($tab as $ligne) {
            //on crée un objet de la classe courante
            $objet = new static();
            //On le charge
            $objet->loadFromTab($ligne);
            //loadFromTab() ne gere pas l'id donc on le rajoute apres
            if(isset($ligne[$this->idChamp])){
                $objet->id = $ligne[$this->idChamp];  
            }
            //On l'ajoute à $result au bon index:
            $result[$objet->id] = $objet;
        }
        return $result;
    }
}