<?php

/*

Templates de fragment

Rôle : Mettre en forme l'affichage de la div recap_pizza
Paramètre : 
    Neant
*/
use library\GestionSessionPizza;
$pizza = GestionSessionPizza::charger();
$pizzaPrix = $pizza->get("prix");
$pizzaTaille = $pizza->get("taille");
$pizzaTailleNom = $pizzaTaille ? $pizzaTaille->get("nom") : null;
$pizzaPate = $pizza->get("pate");
$pizzaPateNom = $pizzaPate ? $pizzaPate->get("nom") : null;
$pizzaBase = $pizza->get("base");
$pizzaBaseNom = $pizzaBase ? $pizzaBase->get("nom") : null;
$ingredientsChoisis = $pizza->chargerIngredientsChoisis();
?>
<div class="container-recap">
    <h2>Récapitulatif de votre pizza</h2>
    <div class="container-option">
        <div class="prix">
            <p>Prix de votre Pizza: <?= htmlentities($pizzaPrix) ? htmlentities($pizzaPrix) . "€" : "Merci de choisir une taille de pizza."; ?></p>
        </div>
        <div class="taille">
            <p>Taille de votre pizza: <?= htmlentities($pizzaTailleNom ==! null) ? htmlentities($pizzaTailleNom) : "Merci de choisir une taille de pizza."; ?></p>
        </div>
        <div class="pate">
            <p>Pate de votre pizza: <?= htmlentities($pizzaPateNom ==! null) ? htmlentities($pizzaPateNom) : "Merci de choisir un type de pate à votre pizza."; ?></p>
        </div>
        <div class="base">
            <p>Base de votre pizza: <?= htmlentities($pizzaBaseNom ==! null) ? htmlentities($pizzaBaseNom) : "Merci de choisir un type de base à votre pizza."; ?></p>
        </div>
        <div class="ingredients">
            <!--<p>Ingredients de votre pizza: </p>-->
            <?php
                //Pour chaque Ingredient Choisi, je crée une card-ingredient-choisi
                foreach($ingredientsChoisis as $ingredientChoisi){
                    include "templates/fragments/card_ingredient_choisi.php";
                }
            ?>
        </div>
        <?php
            //Je vérifie si la pizza est composée d'une pate, d'une taille, d'une base et de 2 ingredients au moins
            $taille = $pizza->get("taille");
            $pate = $pizza->get("pate");
            $base = $pizza->get("base");
            $nbrIngredients = $pizza->compterIngredients();
            if($taille && $pate && $base && $nbrIngredients >= 2){
                //Si oui, je fais apparaitre le bouton commander
                ?>
                <div class="cta">
                    <form action="https://api.mywebecom.ovh/play/mypizza/order.php" method="GET">
                        <input type="hidden" name="id" value="<?= htmlentities($pizza->id()) ?>">
                        <button type="submit" class="primary-btn">Commander</button>
                    </form>
                </div>
                <?php
            }
        ?>
    </div>
</div>