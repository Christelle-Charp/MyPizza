<?php

/*

Templates de fragment

Rôle : générer la card-taille

*/
?>
<div class="card-taille">
    <div class="header-card-taille">
        <h3><?= htmlentities($taille->get("nom")) ?></h3>
    </div>
    <div class="body-card-taille">
        <p class="description"><?= htmlentities($taille->get("description")) ?></p>
    </div>
    <div class="footer-card-taille">
        <input type="radio" id="taille-option-<?= htmlentities($taille->id()) ?>" name="taille" value="<?= htmlentities($taille->id()) ?>" >
        <label for="taille-option-<?= htmlentities($taille->id()) ?>"><p class="prix"><?= htmlentities($taille->get("prix")) ?>€</p></label>
    </div>
</div>