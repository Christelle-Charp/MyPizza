<?php

/*
Templates de fragment
Rôle : générer la card-base
*/
?>
<div class="card-base">
    <div class="header-card-base">
        <input type="radio" id="base-option-<?= htmlentities($base->id()) ?>" name="base" value="<?= htmlentities($base->id()) ?>" >
        <label for="base-option-<?= htmlentities($base->id()) ?>"><h3><?= htmlentities($base->get("nom")) ?></h3></label>
    </div>
    <div class="body-card-base">
        <div class="img-card-base">
            <img src="assets/images/pictures/base/<?= htmlentities(basename($base->get("image"))) ?>" alt="base <?= htmlentities($base->get("nom")) ?>">
        </div>
        <div class="encart">
            <p class="description"><?= htmlentities($base->get("description")) ?></p>
        </div>
    </div>
</div>