let currentContactId = null;
let currentIndex = null;
let usableCounter = 0;

$(document).ready(function() {

    // apro modale
    $('#buttonAdd').on('click', function(){
        
        $("#add_edit_contact").modal('show');

        currentContactId = null;
        // Resetta il form
        $('#chiamateTable tbody').empty();
        $('#contact_form')[0].reset();
        $('#idModalEdit').attr('value', '');
        $('#name, #surname, #phone_number, #address, #sex').attr('readonly', false);
        $('#fotoFilename').attr('type', 'file');
        $('#fotoFilename').attr('disabled', false);
        $('#buttonSave, #sex').attr('disabled', false);
        $('#buttonEditInForm').attr('disabled', true);
        $('button[type="submit"]').show();
        $('#fotoFilenameLabel').show();
        // Resetta l'immagine
        $('#editImage').attr('src', '');
        // $('#fotoFilenameLabel').hide();
        // nascondi chiamate
        $('#container_chiamate').hide();
        $('.form-control').removeClass('border-0');
        $('#foto-container').hide();
        
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
        console.log('i miei dati: ', datiForm);
        
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
                    console.log('ecco la risposta');
                    console.log(risposta);
                }
            },
            error: function() {
                alert('Errore nell\'invio del form!');
            }
        });



        // $("#add_edit_contact").modal('hide')
    });

    // mostro dati da modificare
    $('.buttonEdit, .buttonShow').on('click', function(){

        if ($(this).hasClass('buttonShow')) {
            usableCounter = 1;
        } else {
            usableCounter = 0;
        }

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
                // $('#buttonEditInForm').attr('data-id', currentContactId);

                let chiamateTbody = $('#chiamateTable tbody');
                chiamateTbody.empty(); // rimuovi le righe precedenti

                // popola la tabella
                response.chiamate.forEach(function(chiamata){
                    currentIndex ++;
                    let buttonRemoveChiamata = '<button type="button" class="btn btn-danger removeChiamata">x</button>';
                    let buttonEditChiamata = '<button type="button" class="btn btn-warning editChiamata">M</button>';
                    if (usableCounter) {
                        buttonRemoveChiamata = '<button type="button" class="btn btn-danger removeChiamata d-none">x</button>';
                        buttonEditChiamata = '<button type="button" class="btn btn-warning editChiamata d-none">x</button>';
                    }
                    let row =
                        `<tr>
                            <td><input type="text" name="chiamate[${currentIndex}][id]" value="${chiamata.id}" class="border-0" readonly></td>
                            <td><input type="text" name="chiamate[${currentIndex}][id_contatto]" value="${chiamata.id_contatto}" class="border-0" readonly></td>
                            <td><input type="date" name="chiamate[${currentIndex}][date]" value="${chiamata.date}" class="editableInput border-0" readonly></td>
                            <td><input type="time" name="chiamate[${currentIndex}][time]" value="${chiamata.time}" class="editableInput border-0" readonly></td>
                            <td><textarea name="chiamate[${currentIndex}][note]" class="editableInput border-0" readonly>${chiamata.note}</textarea>
                            </td>
                            <td class="d-flex gap-1">
                                ${buttonRemoveChiamata}
                                ${buttonEditChiamata}
                            </td>
                        </tr>`;
                    chiamateTbody.append(row);

                });

            // Controllo se ci sono chiamate
            if (response.chiamate.length === 0) {
                $('#noCallsMessage').show();
                $('#chiamateTable').hide();
            } else {
                $('#noCallsMessage').hide();
                $('#chiamateTable').show();
            }
                
            },
            error: function(error) {
                console.error('Errore Ajax:', error);
            }
        });

        // abilito edit delle chiamate
        $(document).on('click', '.editChiamata', function() {
            let parentRow = $(this).closest('tr');
            let editableElements = parentRow.find('input.editableInput, textarea.editableInput');
            editableElements.attr('readonly', false);
            editableElements.addClass('bg-warning');
            editableElements.removeClass('border-0');
        });

        // logica per show senza modifica.
        // Resetta gli attributi per entrambi i casi (modifica e visualizzazione)
        $('#name, #surname, #phone_number, #address, #sex').attr('readonly', false);
        $('#fotoFilenameLabel').show();
        $('#fotoFilename').attr('type', 'file');
        $('#buttonSave, #sex').attr('disabled', false);
        $('button[type="submit"]').show();
        $('button[id="addChiamata"]').show();
        $('#container_chiamate').show();
        $('.form-control').removeClass('border-0');
        $('#foto-container').show();
        

        // Poi, se è il caso di '.buttonShow', applica le modifiche appropriate
        if ($(this).hasClass('buttonShow')) {
            $('#name, #surname, #phone_number, #address, #sex').attr('readonly', true);
            $('#fotoFilename').attr('type', 'hidden');
            $('#fotoFilenameLabel').hide();
            $('#buttonSave, #sex').attr('disabled', true);
            $('#buttonEditInForm').attr('disabled', false);
            $('button[type="submit"]').hide(); 
            $('button[id="addChiamata"]').hide();
            $('.form-control').addClass('border-0');
        }

        // Mostra la modale
        $("#add_edit_contact").modal('show');

    });

    // aggiongo chiamata

    $('#addChiamata').on('click', function() {

        // mostro la tabella 
        $('#noCallsMessage').hide();
        $('#chiamateTable').show();
        
        // creo una riga con campi vuoti
        event.preventDefault();
        currentIndex ++;
        let newRow = `
        <tr>
            <td><input type="text" name="chiamate[${currentIndex}][id]" class="border-0" readonly></td>
            <td><input type="text" name="chiamate[${currentIndex}][id_contatto]" value="${currentContactId}" class="border-0" readonly></td>
            <td><input type="date" name="chiamate[${currentIndex}][date]"></td>
            <td><input type="time" step="1" name="chiamate[${currentIndex}][time]" value=""></td>
            <td><textarea name="chiamate[${currentIndex}][note]" ></textarea></td>
            <td class="d-flex gap-1">
                <button type="button" class="btn btn-danger removeChiamata">x</button>
            </td>
        </tr>`;
    
        $('#chiamateTable tbody').append(newRow);
    });
    
    // Per rimuovere una riga
    $(document).on('click', '.removeChiamata', function() {
        $(this).closest('tr').remove();
    });
    
});
