
function toggleFilters() {
    document.getElementById("filtres").classList.toggle("hidden");
}

// Fonction pour mettre à jour l'affichage du prix
function updatePrixValue(val) {
    document.getElementById('prix-value').textContent = val + ' €';
}

// Fonction pour mettre à jour l'affichage de la surface
function updateSurfaceValue(val) {
    document.getElementById('surface-value').textContent = val + ' m²';
}

// Fonction pour sélectionner un type de logement
function toggleTypeOption(element) {
    // Trouver tous les éléments avec la classe 'type-option'
    const options = document.querySelectorAll('.type-option');

    // Retirer la classe 'active' de tous les éléments
    options.forEach(option => {
        option.classList.remove('active');
    });

    // Ajouter la classe 'active' à l'élément cliqué
    element.classList.add('active');

    // Sélectionner le bouton radio correspondant
    const radio = element.querySelector('input[type="radio"]');
    radio.checked = true;
}

// Fonction pour appliquer les filtres
// Mettre à jour la fonction appliquerFiltres dans recherche_logement.js
function appliquerFiltres() {
    // Récupérer les valeurs des filtres
    const destination = document.getElementById('searchInput').value;
    const dateDebut = document.getElementById('start-date').value;
    const dateFin = document.getElementById('end-date').value;
    const voyageurs = document.getElementById('voyageurs').value;
    const typeLogement = document.querySelector('input[name="type-logement"]:checked').id;
    const prixMax = document.getElementById('prix-max').value;
    const surfaceMin = document.getElementById('surface-min').value;

    // Préparation des données pour l'envoi AJAX
    const data = new FormData();
    data.append('destination', destination);
    data.append('dateDebut', dateDebut);
    data.append('dateFin', dateFin);
    data.append('voyageurs', voyageurs);
    data.append('typeLogement', typeLogement);
    data.append('prixMax', prixMax);
    data.append('surfaceMin', surfaceMin);
    data.append('action', 'rechercher');

    // Afficher un indicateur de chargement
    document.getElementById('resultats').innerHTML = '<div class="loading">Recherche en cours...</div>';

    // Envoi de la requête AJAX
    fetch('../traitement_recherche.php', {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
        .then(resultats => {
            // Traitement des résultats
            afficherResultats(resultats);

            // Fermer le panneau de filtres
            toggleFilters();
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
            document.getElementById('resultats').innerHTML = '<p>Une erreur est survenue lors de la recherche. Veuillez réessayer.</p>';
        });
}

function afficherResultats(resultats) {
    const container = document.getElementById('resultats');

    // Vider le conteneur
    container.innerHTML = '';

    // Vérifier s'il y a des résultats
    if (resultats.length === 0) {
        container.innerHTML = '<p class="no-result">Aucun logement ne correspond à vos critères de recherche.</p>';
        return;
    }

    // Récupérer la liste des favoris avant d'afficher les résultats
    fetch('get_favoris.php')
        .then(response => response.json())
        .then(favoris => {
            // Créer un ensemble pour une recherche efficace
            const favorisSet = new Set(favoris);

            // Créer un élément pour chaque résultat
            resultats.forEach(logement => {
                // Créer la carte de logement
                const carteLogement = document.createElement('div');
                carteLogement.className = 'carte-logement';
                carteLogement.onclick = function() {
                    window.location.href = 'detail_annonce.php?id=' + logement.id;
                };

                // Formater le prix
                const prix = parseFloat(logement.prix_par_personne).toFixed(2);

                // Déterminer le type de logement en français
                let typeLogementText = 'logement entier';
                if (logement.type_location === 'colocation') {
                    typeLogementText = 'colocation';
                }

                // Vérifier si ce logement est en favoris
                const estFavori = favorisSet.has(parseInt(logement.id));
                const heartClass = estFavori ? 'fa-solid' : 'fa-regular';

                // Créer le HTML pour cette carte
                carteLogement.innerHTML = `
                    <div class="favoris-icon" data-id="${logement.id}" onclick="toggleFavori(event, ${logement.id})">
                        <i class="fa-heart ${heartClass}"></i>
                    </div>
                    <div class="image-logement">
                        <img src="${logement.photo_principale || 'default.jpg'}" alt="${logement.titre}">
                    </div>
                    <div class="info-logement">
                        <h3>${logement.titre}</h3>
                        <p class="localisation">${logement.ville}, ${logement.pays}</p>
                        <p class="type">${typeLogementText}</p>
                        <p class="details">${logement.surfaces} m² · ${logement.places} voyageurs</p>
                        <p class="prix">${prix} € <span>par personne et par nuit</span></p>
                    </div>
                `;

                // Ajouter la carte au conteneur
                container.appendChild(carteLogement);
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des favoris:', error);
            // Affiche quand même les résultats sans icônes de favoris marquées
            afficherResultatsSansFavoris(resultats);
        });
}

function afficherResultatsSansFavoris(resultats) {
    const container = document.getElementById('resultats');

    resultats.forEach(logement => {
        // Code similaire à la fonction principale mais sans la vérification des favoris
        const carteLogement = document.createElement('div');
        carteLogement.className = 'carte-logement';
        carteLogement.onclick = function() {
            window.location.href = 'detail_annonce.php?id=' + logement.id;
        };

        const prix = parseFloat(logement.prix_par_personne).toFixed(2);
        let typeLogementText = logement.type_location === 'colocation' ? 'colocation' : 'logement entier';

        carteLogement.innerHTML = `
            <div class="favoris-icon" data-id="${logement.id}" onclick="toggleFavori(event, ${logement.id})">
                <i class="fa-regular fa-heart"></i>
            </div>
            <div class="image-logement">
                <img src="${logement.photo_principale || 'default.jpg'}" alt="${logement.titre}">
            </div>
            <div class="info-logement">
                <h3>${logement.titre}</h3>
                <p class="localisation">${logement.ville}, ${logement.pays}</p>
                <p class="type">${typeLogementText}</p>
                <p class="details">${logement.surfaces} m² · ${logement.places} voyageurs</p>
                <p class="prix">${prix} € <span>par personne et par nuit</span></p>
            </div>
        `;

        container.appendChild(carteLogement);
    });
}

function toggleFavori(event, logementId) {
    event.stopPropagation(); // empêche d'ouvrir la page de détail

    const icon = event.currentTarget.querySelector('i');
    const isActive = icon.classList.contains('fa-solid');

    fetch('ajouter_favoris.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id_logement=${logementId}&action=${isActive ? 'remove' : 'add'}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Changer l'icône (plein ou vide)
                icon.classList.toggle('fa-solid');
                icon.classList.toggle('fa-regular');
            } else {
                console.error("Erreur: ", data.message);
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function retirerFavori(idLogement) {
    fetch('retirer_favoris.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_logement=${idLogement}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const element = document.getElementById('favori-' + idLogement);
                if (element) {
                    element.remove();
                }
            } else {
                console.error("Erreur: ", data.message);
            }
        })
        .catch(error => console.error('Erreur lors de la suppression du favori:', error));
}


// Fonction utilitaire pour formater les dates
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// S'assurer que la recherche est effectuée aussi lorsqu'on appuie sur le bouton de recherche
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('filtrer-btn').addEventListener('click', appliquerFiltres);

    // Ajouter un événement pour permettre la recherche en appuyant sur Entrée dans le champ de recherche
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            appliquerFiltres();
            e.preventDefault();
        }
    });

    // Charger tous les logements au chargement de la page
    appliquerFiltres();
});

// Initialiser les dates avec la date d'aujourd'hui et demain
window.onload = function() {
    const today = new Date();
    const tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);

    document.getElementById('start-date').valueAsDate = today;
    document.getElementById('end-date').valueAsDate = tomorrow;

    // Activer par défaut le premier type de logement
    document.querySelector('.type-option').classList.add('active');
}
