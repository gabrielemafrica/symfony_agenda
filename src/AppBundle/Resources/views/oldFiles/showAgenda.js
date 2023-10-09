$(document).ready(function() {

    //* add with entity form

    $('.buttonShow').on('click', function(){
        
        // prelevo i miei dati
        const id = $(this).data("id");
        const name = $(this).data('name');
        const surname = $(this).data('surname');
        const phoneNumber = $(this).data('phone');
        const address = $(this).data('address');
        const sex = $(this).data('sex');
        const fotoFilename = $(this).data('foto');
        console.log(name);

        // Popolo la modale con i dati
        $('#ModalIdDelete').val(id);
        $('#modalImage').attr('src', fotoFilename);
        $('#modalName').text(name + ' ' + surname);
        $('#modalPhone').text(phoneNumber);
        $('#modalAddress').text(address);
        $('#modalSex').text(sex);

        //popolo i dati del bottone edit
        $('#editButtonInShow').attr('data-id', id);
        $('#editButtonInShow').attr('data-name', name);
        $('#editButtonInShow').attr('data-surname', surname);
        $('#editButtonInShow').attr('data-phone', phoneNumber);
        $('#editButtonInShow').attr('data-address', address);
        $('#editButtonInShow').attr('data-sex', sex);
        $('#editButtonInShow').attr('data-foto', fotoFilename);
        
        // Mostra la modale
        $("#modal_show").modal('show');

    });

    $('#buttonCloseShow').on('click', function(){

        $("#modal_show").modal('hide');
    });
});