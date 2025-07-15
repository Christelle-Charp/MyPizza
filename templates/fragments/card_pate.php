<?php

/*

Templates de fragment

Rôle : générer la card-pate

*/
?>
<div class="card-pate">
    <div class="header-card-pate">
        <input type="radio" id="pate-option-<?= htmlentities($pate->id()) ?>" name="pate" value="<?= htmlentities($pate->id()) ?>" >
        <label for="pate-option-<?= htmlentities($pate->id()) ?>"><h3><?= htmlentities($pate->get("nom")) ?></h3></label>
    </div>
    <div class="body-card-pate">
        <p class="description"><?= htmlentities($pate->get("description")) ?></p>
    </div>
</div>