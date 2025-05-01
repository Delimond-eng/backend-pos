document.addEventListener('DOMContentLoaded', function (e){
    //Remise des zones de saisie en blanc
    document.getElementById('agent-form').reset();

    // Écouteur d'événement onchange pour le select du ministère
    document.getElementById('ministere-select').addEventListener('change', function() {
        const ministryId = this.value; // Récupérer la valeur sélectionnée du ministère
        makeSelectEmpty('secretariat-select', 'un secrétariat');
        makeSelectEmpty('direction-select', 'une direction');
        makeSelectEmpty('division-select', 'une division');
        makeSelectEmpty('bureau-select', 'un bureau');
        loadStep('secretariats', 'secretariat-select', ministryId, 'secretariat_libelle')
            .then(() =>console.log('secretariats loaded'));
    });

    // Écouteur d'événement onchange pour le select du secrétariat
    document.getElementById('secretariat-select').addEventListener('change', function() {
        const secretariatId = this.value; // Récupérer la valeur sélectionnée du secrétariat
        makeSelectEmpty('direction-select', 'une direction');
        makeSelectEmpty('division-select', 'une division');
        makeSelectEmpty('bureau-select', 'un bureau');
        loadStep('directions', 'direction-select', secretariatId, 'direction_libelle')
            .then(() => console.log('directions loaded'));
    });

    // Écouteur d'événement onchange pour le select de la direction
    document.getElementById('direction-select').addEventListener('change', function() {
        const directionId = this.value; // Récupérer la valeur sélectionnée de la direction
        makeSelectEmpty('division-select', 'une division');
        makeSelectEmpty('bureau-select', 'un bureau');
        loadStep('divisions', 'division-select', directionId, 'division_libelle')
            .then(() => {
                console.log('divisions loaded')
            });
    });

    document.getElementById('division-select').addEventListener('change', function() {
        const divisionId = this.value; // Récupérer la valeur sélectionnée de la division
        makeSelectEmpty('bureau-select', 'un bureau');
        loadStep('bureaux', 'bureau-select', divisionId, 'bureau_libelle')
            .then(() => {
                console.log('bureaux loaded')
            });
    });
});

function loadOptions(selectId, options, labelKey) {
    const selectElement = document.getElementById(selectId);
    // Ajouter chaque option à partir des données reçues
    options.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option.id;
        optionElement.textContent = option[labelKey];
        selectElement.appendChild(optionElement);
    });
}

// Fonction pour charger les options pour une étape donnée
function loadStep(step, selectId, parentId, labelKey) {
    return fetch('/load.' + step + '/' + parentId)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            loadOptions(selectId, data, labelKey);
        });
}

function makeSelectEmpty(selectId, message){
    document.getElementById(selectId).innerHTML = `<option selected hidden>Sélectionnez ${message}...</option>`;
}



/**
 * Permet de soumettre les infos saisies pour un agent
 * */
function creerAgent() {
    const form = document.querySelector('#agent-form');
    const formData = new FormData(form);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);

    console.log(formData)

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        fetch('/agents.create', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: !1,
                    timer: 1500,
                });
                form.reset();
            })
            .catch(error => {
                console.error('Une erreur s\'est produite:', error);
            });
    });
}

