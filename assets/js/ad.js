
$('#add-image').click(function(){
    // Je recupère le numéro du future champ que je vais créer
    // const index = $('#ad_images div.form-group').length;
    const index = +$('#widgets-counter').val();
    $('#widgets-counter').val(index + 1);
    
    console.log(index);

    // Je récupère le prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    // Je gère le bouton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter(){
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();