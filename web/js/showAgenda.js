$(document).ready(function() {

    //* add with entity form

    $('.buttonShow').on('click', function(){
        
        // prelevo i miei dati
        const name = $(this).data('name');
        const surname = $(this).data('surname');
        const phoneNumber = $(this).data('phone');
        const address = $(this).data('address');
        const sex = $(this).data('sex');
        const fotoFilename = $(this).data('foto');

        // Popolo la modale con i dati
        $('#modalImage').attr('src', fotoFilename);
        $('#modalName').text(name + ' ' + surname);
        $('#modalPhone').text(phoneNumber);
        $('#modalAddress').text(address);
        $('#modalSex').text(sex);
        
        // Mostra la modale
        $("#modal_show").modal('show');

    });

    $('#buttonCloseShow').on('click', function(){

        $("#modal_show").modal('hide');
    });
});