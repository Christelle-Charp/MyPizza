<?php
/*
Classe décrivant l'objet Pizza du modèle conceptuel
*/

namespace modele;

class Pizza extends _model{
    //Décrire l'objet réel: attributs pour décrire l'objet
    protected $table = "pizzas";
    protected $attributs = ["taille", "pate", "base", "prix_base", "prix"];      //liste simple des noms des attributs d'un objet sans l'id
    protected $liens = ["taille"=>"modele\\Taille", "pate"=>"modele\\Pate", "base"=>"modele\\Base"];          //tableau [nomChamp=>objetPointé, ...]
    protected $liensMultiples = ["ingredients"=>"modele\\Ingredient"]; //tableau [nomChamp=>objetPointé, ...]
    protected $relation = ["ingredients_choisis"=>"id"];       //tableau [nomDeLaTable=>champLiantTable]

    //Méthodes surchargées

    function insert(){
        //Role: surcharger la methode insert() avec les attribut à null au moment de la création de la pizza car aucun attribut choisi
        //Parametre: neant
        //Retour: true si ok, sinon false

        //1ere étape, on vérifie si déja inséré
        if($this->is()){
            return false;
        }
        //On prérempli les champs avec null
        foreach($this->attributs as $champ){
            if(!array_key_exists($champ, $this->valeurs)){
                $this->valeurs[$champ] = null;
            }
        }

        //Appel de la méthode parent
        return parent::insert();
    }

    function chargerIngredientsChoisis(){
        //Role: récupérer dans la bdd les ingredients_choisis dont l'id de la pizza correspond à la pizza en cours
        //Parametre: Néant
        //Retour: un tableau contenant les ingredients de la pizza

        //Construction de la requete:
        $sql = "SELECT `id`, `pizza`, `ingredient` FROM `ingredients_choisis` WHERE `pizza`=:id";

        //Construction des parametres:
        $param = [":id"=>$this->id()];

        //J'execute la requete et recupere les lignes du tableau
        $lignes = bddGetRecords($sql, $param);

        //Je le transforme en tableau d'objets en integrant que les objets crées sont des ingredients et je garde l'id de ingredient dans la table associée:
        $ingredients = [];
        foreach($lignes as $ligne){
            if(isset($ligne["ingredient"])){
                $ingredient = new \modele\Ingredient($ligne["ingredient"]);
                $ingredients[]=[
                    "idAssociation"=>$ligne["id"],  //id de ma table ingredients_choisis
                    "ingredientObj"=>$ingredient   //Objet Ingredient qui vient de l'api
                ];

            }
        }

        //Je fais le retour:
        return $ingredients;
    }

    function compterIngredients(){
        //Role: Compter le nombre d'ingredients d'une pizza
        //Parametre: Neant
        //Retour: le nombre d'ingredients

        //Je récupere les ingredients:
        $ingredients = $this->chargerIngredientsChoisis();
        $nbreIngredients = count($ingredients) ?? 0;
        //je fais le retour
        return $nbreIngredients;
    }

    function calculerSupplement(){
        //Role: calculer le prix des supplements en fonction du nombre d'ingredients choisis
        //Parametre: neant
        //Retour: le prix des supplements

        //Je compte les ingredients
        $nbreIngredients = $this->compterIngredients();
        if ($nbreIngredients <= 3 ){
            //Si le nombre est inférieur ou egal à 3, il n'y a pas de supplement
            return 0;
        }
        //J'initie et je charge un objet $taille pour avoir acces au montant du supplement
        $idTaille = $this->get("taille")->id();
        $taille = Taille::factory($idTaille);
        $supplement = $taille->get("supplement");
        //Je calcule le montant du supplement et je le retourne
        return ($nbreIngredients - 3) * $supplement;

    }

    //Surcharge de la methode get()
    function get($nom){
        //Role: Récupérer les ingredients de la pizza en cours avec la methode get
        //Parametre: Neant
        //Retour: un tableau d'objet

        if($nom === "ingredients"){
            return $this->chargerIngredientsChoisis();
        }
        return parent::get($nom);
    }

    function ajouterIngredient($id){
        //Role: ajouter un ingredient dans la table associée ingredients_choisis avec un lien de l'id de la pizza en cours
        //Parametre: 
        //      $id: id de l'ingredient à ajouter
        //Retour: id de l'ingredient choisi sinon 0

        //1ere étape: Vérifier si l'ingredient est déja lié à la pizza en cours
        //Construction de la requete
        $sql= "SELECT COUNT(`id`) AS `compteur_ingredient` FROM `ingredients_choisis` WHERE `pizza`=:id_pizza AND `ingredient`=:id";
        //Construction des parametres
        $param = [":id_pizza"=>$this->id(), ":id"=>$id];
        //J'execute la requete et je recupere la ligne du tableau
        $ligne = bddGetRecord($sql, $param);
        //Je fais mon retour
        $compteur = $ligne["compteur_ingredient"] ?? 0;

        //2eme étape: Si l'ingredient n'existe pas dans les ingredients choisis, je l'ajoute à la table
        if($compteur === 0){
            //Construire la requete
            $sql = "INSERT INTO `ingredients_choisis` SET `pizza`= :id_pizza, `ingredient`=:id";
            //Construire les parametres:
            $param = [":id_pizza"=>$this->id(), ":id"=>$id];
            //On prepare et on execute la requete:
            $req = bddRequest($sql, $param);

            //On vérifie si la requete à marcher:
            if($req == false) {
                return 0;
            }

            //Retour
            global $bdd;
            return $bdd->lastInsertId();
        }
    }

    function supprimerIngredient($id){
        //Role: supprimer un ingredient de la table associée ingredients_choisis
        //Parametre:
        //      $id: id de l'ingredient à supprimer
        //Retour: true si ok sinon false

        //Construction de la requete
        $sql = "DELETE FROM `ingredients_choisis` WHERE `id`=:id";
        //Construction des parametres:
        $param = [":id" => $id];
        //On prepare et on execute la requete:
        $req = bddRequest($sql, $param);
        //On vérifie si la requete à marcher:
        if($req == false) {
            return false;
        } else {
            //Retour
            return true;
        }
    }

    /* Ne pas utiliser car comme on ne maitrise pas la commande (gérée par l'api du client),
     on pourrait supprimer une pizza qui a été commandée car pas de distinction dans la base
     entre une pizza non validée et une pizza commandée.
    function supprimerTousLesIngredients(){
        //Role: supprimer tous les ingredients de la table associée ingredients_choisis liés à la pizza en cours
        //Parametre:
        //      Néant
        //Retour: true si ok sinon false

        //Construction de la requete
        $sql = "DELETE FROM `ingredients_choisis` WHERE `pizza`=:id";
        //Construction des parametres:
        $param = [":id" => $this->id()];
        //On prepare et on execute la requete:
        $req = bddRequest($sql, $param);
        //On vérifie si la requete à marcher:
        if($req == false) {
            return false;
        } else {
            //Retour
            return true;
        }
    }

    function nettoyagePizza(){
        //Role: supprimer tous les ingredients de la table associée ingredients_choisis liés à la pizza en cours et la pizza en cours
        //Parametre:
        //      Néant
        //Retour: true si ok sinon false

        //1ere étape: Supprimer tous les ingredients de la pizza en cours contenu dans la table associées ingredients_choisis
        $suppressionIngredients = $this->supprimerTousLesIngredients();

        //2eme étape: Supprimer la pizza
        $suppressionPizza = $this->delete();

        if(!$suppressionIngredients) echo "Échec suppression ingrédients\n";
        if(!$suppressionPizza) echo "Échec suppression pizza\n";

        //Vérification de la suppression et retour
        if($suppressionIngredients && $suppressionPizza){
            return true;
        } else {
            return false;
        }
    }*/
}