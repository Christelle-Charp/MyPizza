<?php
/*
Template de page complète : Mettre en forme le formulaire de saisie des champs de la pizza
Paramètres : 
    $listTaille: tableau de toutes les tailles
    $listPate: tableau de toutes les pates
    $listBase: tableau de toutes les bases
    $listIngredient: tableau de toutes les ingredients
*/
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "MyPizza: Créer votre pizza";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <main>
        <div class=containerModal id="popup_option"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="creation-pizza">
                    <section class="presentation">
                        <h1><?= $titre ?></h1>
                        <p>Choisissez votre taille, votre type de pate et votre base. A partir de 3 ingrédients inclus! Ensuite 1€ par ingrédient supplémentaire</p>
                    </section>
                    <section class="taille">
                        <h2>Choisissez votre taille:</h2>
                        <div class="container-card-taille">
                            <?php
                            //Pour chaque ligne du tableau $listTaille, je crée une card-taille
                                foreach ($listTaille as $taille) {
                                    include "templates/fragments/card_taille.php";
                                }
                            ?>
                            <!--<div class="card-taille">
                                <div class="header-card-taille">
                                    <h3>Petite</h3>
                                </div>
                                <div class="img-card-taille">
                                    <img src="assets/images/pictures/taille/petite.png" alt="dessin d'une petite pizza">
                                </div>
                                <div class="footer-card-taille">
                                    <input type="radio" id="taille-option-T1" name="taille" value="T1" >
                                    <label for="taille-option-T1"><p>8€</p></label>
                                </div>
                            </div>-->
                        </div>
                    </section>
                    <section class="pate">
                        <h2>Choisissez votre pâte: </h2>
                        <div class="container-card-pate">
                            <?php
                            //Pour chaque ligne du tableau $listPate, je crée une card-pate
                                foreach ($listPate as $pate) {
                                    include "templates/fragments/card_pate.php";
                                }
                            ?>
                            <!--<div class="card-pate">
                                <div class="header-card-pate">
                                    <input type="radio" id="pate-option-P1" name="pate" value="P1" >
                                    <label for="pate-option-P1"><h3>Fine</h3></label>
                                </div>
                                <div class="img-card-pate">
                                    <img src="assets/images/pictures/pate/fine.avif" alt="image d'une pizza pate fine">
                                </div>
                            </div>-->
                        </div>
                    </section>
                    <section class="base">
                        <h2>Choisissez votre base: </h2>
                        <div class="container-card-base">
                            <?php
                            //Pour chaque ligne du tableau $listBase, je crée une card-base
                                foreach ($listBase as $base) {
                                    include "templates/fragments/card_base.php";
                                }
                            ?>
                            <!--<div class="card-base">
                                <div class="header-card-base">
                                    <input type="radio" id="base-option-B1" name="base" value="B1" >
                                    <label for="base-option-B1"><h3>tomate</h3></label>
                                </div>
                                <div class="img-card-base">
                                    <img src="" alt="">
                                </div>
                            </div>-->
                        </div>
                    </section>
                    <section class="ingredients">
                        <h2>Choisissez vos ingrédients: </h2>
                        <div class="container-card-ingredient">
                            <?php
                            //Pour chaque ligne du tableau $listIngredient, je crée une card-ingredient
                                foreach ($listIngredient as $ingredient) {
                                    include "templates/fragments/card_ingredient.php";
                                }
                            ?>
                            <!--<div class="card-ingredient">
                                <div class="header-card-ingredient">
                                    <input type="radio" id="ingredient-option-I1" name="ingredient" value="I1" >
                                    <label for="ingredient-option-I1"><h3>tomates cerises</h3></label>
                                </div>
                                <div class="img-card-ingredient">
                                    <img src="" alt="">
                                </div>
                            </div>-->
                        </div>

                    </section>
                </div>
                <div id="recap_pizza">

                </div>
            </div>
        </div>

    </main>
    <script src="js/fonctions.js"></script>
</body>
</html>