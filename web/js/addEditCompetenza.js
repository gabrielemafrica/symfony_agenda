$(document).ready(function() {

    // Seleziona gli elementi solo una volta per evitare ripetute query al DOM
    const $modal = $("#add_edit_competenze");
    const $competenzaForm = $('#competenza_form');
    const $assegnaCompetenze = $('#container-assegna');
    const $submitButton = $('button[type="submit"]');
    const $formControls = $('.form-control');

    // Funzione per resettare il form e svuotare il contenitore delle competenze
    function resetFormAndContainer() {
        // Resetta il form
        $competenzaForm[0].reset();
        // Rimuove la classe 'border-0' dai controlli del form
        $formControls.removeClass('border-0');
        // Svuota il contenitore delle competenze
        $assegnaCompetenze.empty();
        // Mostra il pulsante di invio
        $submitButton.show();
    }

    // Funzione per costruire una lista di checkbox basata sui dati forniti
    function buildCheckboxList(data, associatedAgendasIds = []) {
        // Inizializza un contenitore per i checkbox con uno scroll verticale
        let checkboxes = '<div class="checkbox-list" style="max-height: 150px; overflow-y: scroll;">';
        data.forEach(function(agenda) {
            // Verifica se l'agenda corrente è associata
            let isChecked = associatedAgendasIds.includes(agenda.id) ? 'checked' : '';
            checkboxes += `
                <div>
                    <input type="checkbox" id="agenda[${agenda.id}]" name="agenda[${agenda.id}]" value="${agenda.id}" ${isChecked}>
                    <label for="agenda[${agenda.id}]">${agenda.name} ${agenda.surname}</label>
                </div>
            `;
        });
        // Chiude il contenitore dei checkbox
        checkboxes += '</div>';
        // Aggiunge la lista di checkbox al contenitore delle competenze
        $assegnaCompetenze.append(checkboxes);
    }

    // Evento per aprire la modale e mostrare le competenze
    $('#buttonAddCompetenza').on('click', function() {
        // Mostra la modale
        $modal.modal('show');
        // Resetta il form e il contenitore
        resetFormAndContainer();
        // Effettua una chiamata AJAX per ottenere le competenze
        $.post('/editCompetenza', null, function(risposta) {
            // Costruisce la lista di checkbox con le competenze ricevute
            buildCheckboxList(risposta);
        }).fail(function() {
            // Gestisce l'errore nel caso la chiamata AJAX non vada a buon fine
            alert('Errore nell\'invio dei dati!');
        });
    });

    // Evento per chiudere la modale
    $('#buttonCloseCompetenze').on('click', function() {
        $modal.modal('hide');
    });

    // Evento per inviare il form con le competenze
    $competenzaForm.submit(function(event) {
        // Previene il comportamento di default del form (cioè il suo invio normale)
        event.preventDefault();
        // Crea un oggetto FormData con i dati del form
        let datiForm = new FormData(this);
        // Effettua una chiamata AJAX per salvare i dati
        $.ajax({
            url: '/saveCompetenza',
            type: 'POST',
            data: datiForm,
            contentType: false,
            processData: false,
            success: function(risposta) {
                // Se i dati sono stati salvati correttamente, reindirizza alla homepage
                if (risposta.status && risposta.status === 'success') {
                    window.location.href = '/';
                } else {
                    // Altrimenti, mostra un messaggio di errore e stampa la risposta
                    alert('Si è verificato un errore durante il salvataggio dei dati.');
                }
            },
            error: function() {
                // Gestisce l'errore nel caso la chiamata AJAX non vada a buon fine
                alert('Errore nell\'invio del form!');
            }
        });
    });

    // Evento per mostrare i dettagli di una competenza e modificarla
    $('.buttonEditCompetenza').on('click', function() {
        const id = $(this).data("id");
        resetFormAndContainer();
        // Effettua una chiamata AJAX per ottenere i dettagli della competenza
        $.ajax({
            type: "GET",
            url: '/editCompetenza',
            data: { id: id },
            success: function(response) {
                // Imposta i valori ricevuti nei campi appropriati del form
                $('#idCompetenza').val(response.id);
                $('#nameCompetenza').val(response.description);

                let associatedAgendasIds = response.agende_associate ? response.agende_associate.map(agenda => agenda.id) : [];
                // Costruisce la lista di checkbox con le agende associate
                buildCheckboxList(response.total_agenda, associatedAgendasIds);
            },
            error: function() {
                // Gestisce l'errore nel caso la chiamata AJAX non vada a buon fine
                alert('Errore nel caricamento dei dati!');
            }
        });
        $modal.modal('show');
    });

    // Evento per eliminare una competenza
    $('.buttonDeleteCompetenza').on('click', function() {
        const id = $(this).data("id");
        // Effettua una chiamata AJAX per eliminare la competenza
        $.ajax({
            url: '/deleteCompetenza',
            type: 'GET',
            data: { id: id },
            success: function(data) {
                // Se l'eliminazione ha avuto successo, reindirizza alla homepage
                if (!data.error) {
                    window.location.href = '/';
                } else {
                    // Altrimenti, mostra un messaggio di errore
                    alert(data.error);
                }
            },
            error: function() {
                // Gestisce l'errore nel caso la chiamata AJAX non vada a buon fine
                alert('Errore durante l\'eliminazione della competenza.');
            }
        });
    });
});
