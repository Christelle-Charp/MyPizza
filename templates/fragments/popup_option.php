<?php

/*

Templates de fragment

Rôle : Mettre en forme l'affichage de la popup option
Paramètre : 
    $ingredient: objet contenant le détail d'un ingredient
*/
?>
<div class="cta-closed">
    <button class="btn-closed" onclick="fermerPopUp()">X</button>
</div>
<div class="header-popup">
    <h3><?=htmlentities($ingredient->get("nom")) ?></h3>
</div>
<div class="body-popup">
    <img src="assets/images/pictures/ingredients/<?= htmlentities(basename($ingredient->get("image"))) ?>" alt="<?= htmlentities($ingredient->get("nom")) ?>">
    <p class="description"><?=htmlentities($ingredient->get("description")) ?></p>
</div>