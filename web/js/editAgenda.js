$(document).ready(function() {

    //* add with entity form

    $('.buttonEdit').on('click', function(){
        
        // prelevo i miei dati
        const id = $(this).data("id");
        const name = $(this).data('name');
        const surname = $(this).data('surname');
        const phoneNumber = $(this).data('phone');
        const address = $(this).data('address');
        const sex = $(this).data('sex');
        const fotoFilename = $(this).data('foto');
        // console.log(name, surname, phoneNumber, address, sex, fotoFilename);
        console.log(id);

        // Popolo la modale con i dati
        $('#imageModalEdit').attr('src', fotoFilename);
        $('#idModalEdit').val(id);
        $('#idModalDelete').val(id);
        $('#nameModalEdit').val(name);
        $('#surnameModalEdit').val(surname);
        $('#phone_numberModalEdit').val(phoneNumber);
        $('#addressModalEdit').val(address);
        $('#sexModalEdit').val(sex); 
        
        // Mostra la modale
        $("#modal_edit").modal('show');
        $("#modal_show").modal('hide');

    });

    $('#buttonCloseEdit').on('click', function(){

        $("#modal_edit").modal('hide');
    });
});