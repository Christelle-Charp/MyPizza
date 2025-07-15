/*
Librairies des fonctions spécifiques générales du projet
*/
//J'écoute les changements des boutons radio taille, base, pate et ingredients 
document.addEventListener("DOMContentLoaded", function(){

    //Charger le recap_pizza au chargement de la page
    setTimeout(chargerRecapPizza, 100);

    const radiosTaille = document.querySelectorAll("input[name='taille']");
    const radiosPate = document.querySelectorAll("input[name='pate']");
    const radiosBase = document.querySelectorAll("input[name='base']");
    const checkboxesIngredient = document.querySelectorAll("input[name='ingredient[]']");

    radiosTaille.forEach(function(radio){
        radio.addEventListener("change", function(){
            let taille = this.value;
            selectionnerTaille(taille);
        })
    })

    radiosPate.forEach(function(radio){
        radio.addEventListener("change", function(){
            let pate = this.value;
            selectionnerPate(pate);
        })
    })

    radiosBase.forEach(function(radio){
        radio.addEventListener("change", function(){
            let base = this.value;
            selectionnerBase(base);
        })
    })

    checkboxesIngredient.forEach(function(checkbox){
        checkbox.addEventListener("change", function(){
            let ingredient = this.value;
            selectionnerIngredient(ingredient);
        })
    })

})
//Je bloque l'ouverture des popup quand je clic sur les checkbox:
document.querySelectorAll('.card-ingredient input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('click', event => {
        event.stopPropagation(); // Empêche le déclenchement du click sur le parent
    });
});

//J'écoute les clic sur mes cards
document.addEventListener("click", function(event) {
    const card = event.target.closest(".card-ingredient, .card-ingredient-choisi");
    if (card && card.hasAttribute("data-id-ingredient")) {
        const id = card.getAttribute("data-id-ingredient");
        demanderIngredient(id);
    }
});

function fermerPopUp(){
    //Role: Fermer la modal
    //Parametre: néant
    //Retour: néant

    const modal = document.getElementById("popup_option");
    if(modal){
        modal.innerHTML="";     //Vider la modal
        modal.style.display="none"; 
    }
}

function chargerRecapPizza(){
    //Role: Charger le recap_Pizza à l'affichage de la page et transmettre le retour à afficherRecapPizza
    //Parametre: 
    //      neant
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "recuperer_recap_pizza_ajax.php";
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherRecapPizza);
}


function selectionnerTaille(taille){
    //Role: Enregistrer le choix de l'utilisateur pour la partie  Taille et transmettre le retour à afficherRecapPizza
    //Parametre: 
    //      taille: l'id de la taille de la pizza en get
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "enregistrer_taille_pizza_ajax.php?taille=" + taille;
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherRecapPizza);
}

function selectionnerPate(pate){
    //Role: Enregistrer le choix de l'utilisateur pour la partie  pate et transmettre le retour à afficherRecapPizza
    //Parametre: 
    //      pate: l'id de la pate de la pizza en get
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "enregistrer_pate_pizza_ajax.php?pate=" + pate;
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherRecapPizza);
}

function selectionnerBase(base){
    //Role: Enregistrer le choix de l'utilisateur pour la partie  base et transmettre le retour à afficherRecapPizza
    //Parametre: 
    //      base: l'id de la base de la pizza en get
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "enregistrer_base_pizza_ajax.php?base=" + base;
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherRecapPizza);
}

function selectionnerIngredient(ingredient){
    //Role: Enregistrer le choix de l'utilisateur pour la partie  ingredient et transmettre le retour à afficherRecapPizza
    //Parametre: 
    //      ingredient: l'id de l'ingredient de la pizza en get
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "enregistrer_ingredient_pizza_ajax.php?ingredient=" + ingredient;
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherRecapPizza);
}

function afficherRecapPizza(fragment){
    //Role: Afficher dans le cadre #recap_pizza le contenu html reçu
    //Parametre:
    //      fragment: code html à afficher
    //Retour: Néant

    //On remplit la div #recap_pizza avec le fragment:
    document.getElementById("recap_pizza").innerHTML = fragment;
}

function supprimerIngredient(ingredient){
    //Role: Supprimer l'ingredient choisi de la pizza et transmettre le retour à afficherRecapPizza
    //Parametre: 
    //      ingredient: l'id de l'ingredient de la pizza en get
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "supprimer_ingredient_pizza_ajax.php?ingredient=" + ingredient;
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherRecapPizza);
}

function demanderIngredient(ingredient){
    //Role: Demander le détail d'un ingredient et transmettre le retour à afficherIngredient
    //Parametre: 
    //      ingredient: l'id de l'ingredient à afficher
    //Retour: Neant

    //Construire l'url à appeler (celle du controler Ajax)
    let url = "afficher_ingredient_ajax.php?ingredient=" + ingredient;
    fetch(url)
        .then(function(reponse){
            return reponse.text();
        })
        .then(afficherIngredient);
    
}

function afficherIngredient(fragment){
    //Role: Afficher dans le cadre #popup_option le contenu html reçu
    //Parametre:
    //      fragment: code html à afficher
    //Retour: Néant

    //On remplit la div #popup_option avec le fragment:
    document.getElementById("popup_option").innerHTML = fragment;
    document.getElementById("popup_option").style.display="flex";
}