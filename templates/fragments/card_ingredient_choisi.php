<?php

/*
Templates de fragment
Rôle : générer la card-ingredient-choisi
*/
$idAssociation = $ingredientChoisi["idAssociation"];
$ingredient=$ingredientChoisi["ingredientObj"];
?>
<div class="card-ingredient-choisi" data-id-ingredient="<?= htmlentities($ingredient->id()) ?>">
    <div class="header-card-ingredient-choisi">
        <h3><?= htmlentities($ingredient->get("nom")) ?></h3>
    </div>
    <div class="img-card-ingredient-choisi">
        <img src="assets/images/pictures/ingredients/<?= htmlentities(basename($ingredient->get('image'))) ?>" alt="<?= htmlentities($ingredient->get('nom')) ?>">
    </div>
    <div class="footer-card-ingredient-choisi">
        <button class="btn-suppression" onclick="event.stopPropagation(); supprimerIngredient(<?= htmlentities($idAssociation) ?>)">X</button>
    </div>
</div>