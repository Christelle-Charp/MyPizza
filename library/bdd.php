<?php

/*
Librairie des fonctions utiles pour accéder à la base de données

Les fonctions utilise la variable global $bdd qui est initialisée dans le programme init.php pour acceder à la base de données

Recap des fonctions et leur retour:
- Préparer et exécuter une requete: bddRequest($sql, $param) (Retourne la requete préparée et exécuté sinon false)
- Retourner 1 enregistrement de la bdd sous forme de tableau indéxé: bddGetRecord($sql, $param =[])
- Retourner plusiers enregistrements de la bdd sous forme de tableau indexé: bddGetRecords($sql, $param = [])
- Insert un enregistrement dans la base de données et retourne la clé primaire créée: bddInsert($table, $valeurs)
- Mettre à jour un enregistrement dans la base de données: bddUpdate($table, $valeurs, $id) (Retourne true si maj ok, sinon false)
- Supprimer un enregistrement dans la base de données: bddDelete($table, $id) (Retourne true si suppression ok, sinon false)

*/

function bddRequest($sql, $param) {
    //Role: préparer et executer une requete et de retourner false ou un objet "PDOstatement"
    // parametre:
    //      $sql: texte de la commande SQL complete avec parametre au format :xxx
    //      $param: tableau de valorisation des :xxx
    //Retour soit false, soit la requete préparée et éxécuté

    //Je prépare la requete:
    //Je commence par appeller la variable globale de la bdd
    global $bdd;
    $req = $bdd->prepare($sql);

    //si $req est false: return false
    if (empty($req)) {
        return false;
    }

    //J'exécute la requete
    $execution = $req->execute($param);
    
    //si la requete retourne true, je retourne $req sinon false
    if($execution) {
        return $req;
    } else {
        //Sinon je retourne false
        return false;
    }
}

function bddGetRecord($sql, $param =[]) {
    //Role: retourne un enregistrement de la bdd sous forme d'un tableau indexé
    //Parametres:
    //       $sql: texte de la commande SQL complete avec parametre au format :xxx
    //      $param: tableau de valorisation des :xxx
    //Retour: soit false si aucune ligne soit la premiere ligne récupérée

    //Je prépare et j'exécute la requete pour récupérer la requete exécutée:
    $req = bddRequest($sql, $param);
    //si $req vaut false: retourne false
    if($req == false) {
        return false;
    }

    //Il me faut la 1ere ligne uniquement:
    $ligne = $req->fetch(PDO::FETCH_ASSOC);
    //le tableau récupéré sous la forme nom du champ en index, valeur en valeur
    //On vérifie que l'on n'a pas récupérer le false:
        if(empty($ligne)) {
            return false;
        } else {
            return $ligne;
        }
}

function bddGetRecords($sql, $param = []) {
    // Rôle : Retourne les lignes récupérées par un SELECT
    // Paramètres :
    //      $sql : texte de la commande SQL complète, avec des paramètres :xxx
    //      $param : tableau de valorisation des paramètres :xxx
    // Retour : un tableau comprenant des tableaux indexés par les noms des colonnes (ou un tableau vide)

    // Préparer et exécuter la requête pour récupérer une requête exécutée : on a une fonction que le fait
    $req = bddRequest($sql, $param);

    if ($req == false) {
        return [];
    }

    // On récupère toutes les lignes de la requêtes (avec sa méthode fetchAll) : cela donne directement ce que l'on veut
    return $req->fetchAll(PDO::FETCH_ASSOC);

}

function bddInsert($table, $valeurs) {
    // Rôle : Insert un enregistrement dans la base de données et retourne la clé primaire créée 
    // Paramètres :
    //      $table : nom de la table dasn la BDD
    //      $valeurs : tableau donnant les valeurs des champs (colonnes de la table) [ "nomChamp1" => valeurAdonner, ... ]
    // Retour : 0 en cas d'échec, la clé primaire créée sinon

    //Il faut créer 2 tableaux à partir du tableau $valeurs qui contient 1 seule ligne mais plusieurs colonnes (champs)
    //      - $param: un tableau sous forme ": nom du champs" = "$valeur du champs" (pas de $this car pas dans objet?)  
    //      - $champs: un tableau sous forme "`nom du champs`" = ":nom du champs" pour la requete 
    $champs = [];
    $param = [];
    //Pour chaque champ de $valeur:
    foreach ($valeurs as $nomChamp => $valeurChamp) {
        //Ajouter à $champs `nomChamp`" = ":nomChamp"
        $champs[] = "`$nomChamp` = :$nomChamp";
        //Ajouter au tableau des paramètre $param[":nomChamp"] = la valeur
        $param[$nomChamp] = $valeurChamp;
    }

    //je met en forme la tableau $champs en séparant chaque $champ par une virgule avec methode implode()
    $champ = implode(", ", $champs);
        
    //On contruit la requete:
    $sql = "INSERT INTO `$table` SET $champ"; 

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

function bddUpdate($table, $valeurs, $idChamp, $id) {
    // Rôle : Mettre à jour un enregistrement dans la base de données
    // Paramètres :
    //      $table : nom de la table dans la BDD
    //      $valeurs : tableau donnant les nouvelles valeurs des champs (colonnes de la table) [ "nomChamp1" => valeurAdonner, ... ]
    //      $id : valeur de la clé primaire (la clé primaire doit s'appeler id)
    // Retour : true si ok, false sinon

     //Il faut créer 2 tableaux à partir du tableau $valeurs qui contient 1 seule ligne mais plusieurs colonnes (champs)
    //      - $param: un tableau sous forme ": nom du champs" = "$valeur du champs" 
    //      - $champs: un tableau sous forme "`nom du champs`" = ":nom du champs" pour la requete 
    $champs = [];
    $param = [];

    //Pour chaque champ de $valeur:
    foreach ($valeurs as $nomChamp => $valeurChamp) {
        //Ajouter à $champs `nomChamp`" = ":nomChamp"
        $champs[] = "`$nomChamp` = :$nomChamp";
        //Ajouter au tableau des paramètre $param[":nomChamp"] = la valeur
        $param[":$nomChamp"] = $valeurChamp;
    }

    //je met en forme la tableau $champs en séparant chaque $champ par une virgule avec methode implode()
    $champ = implode(", ", $champs);

    //Je rajoute $id dans mon tableau de paramètres:
    $param[":id"]=$id;
        
    //On contruit la requete:
    $sql = "UPDATE `$table` SET $champ WHERE `$idChamp` = :id";

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

function bddDelete($table, $idChamp, $id) {
    // Rôle : Supprimer un enregistrement dans la base de données
    // Paramètres :
    //      $table : nom de la table dans la BDD
    //      $id : valeur de la clé primaire (la clé primaire doit s'appeler id)
    // Retour : true si ok, false sinon

    //Construire la requete
    $sql = "DELETE FROM `$table` WHERE `$idChamp` = :id";

    //Déclarer les parametres
    $param = [":id" =>$id];

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