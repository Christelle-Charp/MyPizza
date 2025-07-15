<?php

/*
Templates de fragment
Rôle : générer la card-ingredient
*/
?>
<div class="card-ingredient" data-id-ingredient="<?= htmlentities($ingredient->id()) ?>">
    <div class="header-card-ingredient">
        <input type="checkbox" id="ingredient-option-<?= htmlentities($ingredient->id()) ?>" name="ingredient[]" value="<?= htmlentities($ingredient->id()) ?>" >
        <label for="ingredient-option-<?= htmlentities($ingredient->id()) ?>"><h3><?= htmlentities($ingredient->get("nom")) ?></h3></label>
    </div>
    <div class="img-card-ingredient">
        <img src="assets/images/pictures/ingredients/<?= htmlentities(basename($ingredient->get("image"))) ?>" alt="<?= htmlentities($ingredient->get("nom")) ?>">
    </div>
</div>