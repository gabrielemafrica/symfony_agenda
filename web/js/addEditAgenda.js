let currentContactId = null;

$(document).ready(function() {

    // apro modale
    $('#buttonAdd').on('click', function(){

        currentContactId = null;
        // Resetta il form
        $('#chiamateTable tbody').empty();
        $('#contact_form')[0].reset();
        $('#name, #surname, #phone_number, #address, #sex').attr('readonly', false);
        $('#fotoFilename').attr('type', 'file');
        $('#fotoFilename').attr('disabled', false);
        $('#buttonSave, #sex').attr('disabled', false);
        $('#buttonEditInForm').attr('disabled', true);
        // Resetta l'immagine
        $('#editImage').attr('src', '');
          
        $("#add_edit_contact").modal('show');

    });

    // chiudo modale
    $('#buttonClose').on('click', function(){
       
        $("#add_edit_contact").modal('hide');
    });

    // salvo i dati
    $('#contact_form').submit(function(event) {
        
        event.preventDefault();
        
        // // dati form come oggetto
        // var datiForm = $(this).serialize();

        // // chiamata AJAX
        // $.post('/save', datiForm, function(risposta) {
        //     console.log(risposta);
        //     if (risposta.status && risposta.status === 'success') {
        //         window.location.href = '/';
        //     } else {
        //         alert('Si è verificato un errore durante il salvataggio dei dati.');
        //     }
        // }).fail(function() {
        //     alert('Errore nell\'invio del form!');
        // });

        // dati form come oggetto FormData
        var datiForm = new FormData(this);

        // chiamata AJAX
        $.ajax({
            url: '/save',
            type: 'POST',
            data: datiForm,
            contentType: false, // Il tipo di contenuto deve essere impostato su false per forzare jQuery a non processare i dati inviati
            processData: false, // Imposta processData su false per impedire a jQuery di convertire i dati in una stringa di query
            success: function(risposta) {
                console.log(risposta);
                if (risposta.status && risposta.status === 'success') {
                    window.location.href = '/';
                } else {
                    alert('Si è verificato un errore durante il salvataggio dei dati.');
                }
            },
            error: function() {
                alert('Errore nell\'invio del form!');
            }
        });



        $("#add_edit_contact").modal('hide')
    });

    // mostro dati da modificare
    $('.buttonEdit, .buttonShow').on('click', function(){

        // prelevo i miei dati
        const id = $(this).data("id");
        
        // Resetta il form
        $('#contact_form')[0].reset();

        // Resetta l'immagine
        $('#editImage').attr('src', '');
        $('#buttonEditInForm').attr('data-id', '');
        
 
       //shearch in database
        $.ajax({
            type: "GET",
            url: '/editCall',
            data: {id: id},
            success: function(response) {
                currentContactId = response.id;
                $('#idModalEdit').val(response.id);
                $('#name').val(response.name);
                $('#surname').val(response.surname);
                $('#phone_number').val(response.phone_number);
                $('#address').val(response.address);
                $('#sex').val(response.sex);
                $('#editImage').attr('src', response.fotoFilename);
                $('#buttonEditInForm').attr('data-id', currentContactId);

                let chiamateTbody = $('#chiamateTable tbody');
                chiamateTbody.empty(); // rimuovi le righe precedenti

                // popola la tabella
                response.chiamate.forEach(function(chiamata){
                    let row =
                        `<tr>
                            <td>${chiamata.id}</td>
                            <td><input type="date" name="chiamate[${chiamata.id}][date]" value="${chiamata.date}"></td>
                            <td><input type="time" name="chiamate[${chiamata.id}][time]" value="${chiamata.time}"></td>
                            <td><input type="text" name="chiamate[${chiamata.id}][note]" value="${chiamata.note}"></td>
                            <td class="d-flex gap-1">
                                <button type="button" class="btn btn-danger removeChiamata">x</button>
                                <button type="button" class="btn btn-warning">M</button>
                            </td>
                        </tr>`;
                    chiamateTbody.append(row);
                })
                
            },
            error: function(error) {
                console.error('Errore Ajax:', error);
            }
        });
        
        // logica per show.
        // Resetta gli attributi per entrambi i casi (modifica e visualizzazione)
        $('#name, #surname, #phone_number, #address, #sex').attr('readonly', false);
        $('#fotoFilename').attr('type', 'file');
        $('#buttonSave, #sex').attr('disabled', false);
        $('#buttonEditInForm').attr('disabled', true);

        // Poi, se è il caso di '.buttonShow', applica le modifiche appropriate
        if ($(this).hasClass('buttonShow')) {
            $('#name, #surname, #phone_number, #address, #sex').attr('readonly', true);
            $('#fotoFilename').attr('type', 'hidden');
            $('#buttonSave, #sex').attr('disabled', true);
            $('#buttonEditInForm').attr('disabled', false);
        }


        
        // Mostra la modale
        $("#add_edit_contact").modal('show');

    });

    // aggiongo chiamata

    $('#addChiamata').on('click', function() {
        let newRow = `
        <tr>
            <form class="chiamataForm">
                <td><input type="hidden" name="id_contatto" value="${currentContactId}"></td>
                <td><input type="date" name="date" value=""></td>
                <td><input type="time" name="time" value=""></td>
                <td><input type="text" name="nota" value=""></td>
                <td class="d-flex gap-1">
                    <button type="button" class="btn btn-danger removeChiamata">x</button>
                    <button type="button" class="btn btn-success saveChiamata">+</button>
                </td>
            </form>
        </tr>`;
    
        $('#chiamateTable tbody').append(newRow);
        console.log();
    });
    
    // Per rimuovere una riga
    $(document).on('click', '.removeChiamata', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.saveChiamata', function() {
        let parentRow = $(this).closest('tr');
        saveChiamata(parentRow);
    });

    function saveChiamata(row) {
        let formElement = row.find('form.chiamataForm')[0];
        let datiForm = new FormData(formElement);
        console.log('dati della chiamata', datiForm);

        $.ajax({
            type: "POST",
            url: '/save-chiamata',
            data: datiForm,
            contentType: false, // Imposta a false per permettere a jQuery di non impostare l'intestazione Content-Type
            processData: false, // Imposta a false per impedire a jQuery di trasformare i dati FormData in una stringa di query
            success: function(response) {
                console.log(response);
                if (response.status && response.status === 'success') {
                    row.find('input, select').attr('disabled', true);
                    row.find('.saveChiamata').attr('disabled', true);
                } else {
                    alert('Si è verificato un errore durante il salvataggio dei dati.');
                }
                }, error :function(){
                    alert('Errore nell\'invio del form!');
                }
            });

        // $.post('/save-chiamata', datiForm, function(risposta) {
        //     console.log(risposta);
        //     if (risposta.status && risposta.status === 'success') {
                
        //         window.location.href = '/';
        //     } else {
        //         alert('Si è verificato un errore durante il salvataggio dei dati.');
        //     }
        // }).fail(function() {
        //     console.error('Errore AJAX:', textStatus, errorThrown);
        //     alert('Errore nell\'invio del form!');
        // });
    }
    
});

