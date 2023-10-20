
$(document).ready(function() {
 
    // apro modale
    $('#buttonAddCompetenza').on('click', function(){
        
        $("#add_edit_competenze").modal('show');
        // Resetta il form
        $('#competenza_form')[0].reset();
        $('.form-control').removeClass('border-0');
        $('button[type="submit"]').show();

        let assegnaCompetenze = $('#container-assegna');
        assegnaCompetenze.empty();

        // popolo agenda

        const id = null;
        $.post('/editCompetenza', id, function(risposta) {
            console.log(risposta);
        
            // Inizializza un contenitore per i checkbox
            let checkboxes = '<div class="checkbox-list" style="max-height: 150px; overflow-y: scroll;">';
        
            risposta.forEach(function(agenda){
                checkboxes += `
                <div>
                    <input type="checkbox" id="agenda[${agenda.id}]" name="agenda[${agenda.id}]" value="${agenda.id}">
                    <label for="agenda[${agenda.id}]">${agenda.name} ${agenda.surname}</label>
                </div>
                `;
            });
        
            // Chiudi il contenitore dei checkbox
            checkboxes += '</div>';
        
            assegnaCompetenze.append(checkboxes);
        
        }).fail(function() {
            alert('Errore nell\'invio dei dati!');
        });
        
    });

    // chiudo modale
    $('#buttonCloseCompetenze').on('click', function(){
       
        $("#add_edit_competenze").modal('hide');
    });

    // salvo i dati
    $('#competenza_form').submit(function(event) {
        
        event.preventDefault();

        var datiForm = new FormData(this);
        console.log('i miei dati competenze: ', datiForm);
        
        // chiamata AJAX
        $.ajax({
            url: '/saveCompetenza',
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

    });

    // mostro dati da modificare
    $('.buttonEditCompetenza').on('click', function(){

        // prelevo i miei dati
        const id = $(this).data("id");
        
        // Resetta il form
        $('#competenza_form')[0].reset();
        $('.form-control').removeClass('border-0');
        let assegnaCompetenze = $('#container-assegna');
        assegnaCompetenze.empty();

       //shearch in database
        $.ajax({
            type: "GET",
            url: '/editCompetenza',
            data: {id: id},
            success: function(response) {
                console.log('ecco i miei dati: ', response);

                $('#idCompetenza').val(response.id);
                $('#nameCompetenza').val(response.description);

                // pendo ID da agende_associate per facilitare la verifica
                let associatedAgendasIds = [];

                if (response.agende_associate && response.agende_associate.length > 0) {
                    associatedAgendasIds = response.agende_associate.map(agenda => agenda.id);
                }

                // Inizializza un contenitore per i checkbox
                let checkboxes = '<div class="checkbox-list" style="max-height: 150px; overflow-y: scroll;">';
            
                response.total_agenda.forEach(function(agenda){

                    // Controlla se l'agenda è associata
                    let isChecked = associatedAgendasIds.includes(agenda.id) ? 'checked' : '';

                    checkboxes += `
                    <div>
                        <input type="checkbox" id="agenda[${agenda.id}]" name="agenda[${agenda.id}]" value="${agenda.id}" ${isChecked}>
                        <label for="agenda[${agenda.id}]">${agenda.name} ${agenda.surname}</label>
                    </div>
                    `;
                });
                checkboxes += '</div>';
                assegnaCompetenze.append(checkboxes);

            },
            error: function() {
                alert('Errore nel caricamento dei dati!');
            }


        });
        $('button[type="submit"]').show();
        // Mostra la modale
        $("#add_edit_competenze").modal('show');

    });

    // elimina competenza
    $('.buttonDeleteCompetenza').on('click', function(){
        // prelevo i miei dati
        const id = $(this).data("id");
    
            $.ajax({
                url: '/deleteCompetenza',
                type: 'GET',
                data: { id: id },
                success: function(data) {
                    console.log(data);
                    if (data.error) {
                        alert(data.error);
                    } else {
                        window.location.href = '/';
                    }
                },
                error: function() {
                    alert('Errore durante l\'eliminazione della competenza.');
                }
            });
    
    })
    
});
