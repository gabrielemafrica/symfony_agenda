$(document).ready(function() {

    //* add with entity form

    $('#buttonAddWithEntityForm').on('click', function(){
        
        //resettare tutti i campi
        
        $("#modal_nominativiEntityForm").modal('show');

    });

    $('#buttonCloseEntityForm').on('click', function(){

        $("#modal_nominativiEntityForm").modal('hide');
    });

    //* add with normal form

    $('#buttonAdd').on('click', function(){
        
        //resettare tutti i campi
        
        $("#modal_nominativi").modal('show');

    });

    $('#buttonClose').on('click', function(){

        $("#modal_nominativi").modal('hide');
    });
    
    $('#buttonSave').on('click', function(){
        
        // Prendo i valori dal form
        let formData = new FormData($('#agendaForm')[0]);

        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        

        $.ajax({
            type: "POST",
            url: '/add-modal',
            data: formData,
            processData: false,  // indica a jQuery di non processare i dati
            contentType: false,  // indica a jQuery di non impostare l'intestazione del tipo di contenuto
            success: function(response) {
                console.log('Risposta dal server:', response);
                window.location.href = '/';
            },
            error: function(error) {
                console.error('Errore Ajax:', error);
            }
        });
        
        $("#modal_nominativi").modal('hide')
        
    });
});