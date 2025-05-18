document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du calcul de prix
    initDateCalculation();

    // Mise à jour du total lorsque le nombre de voyageurs change
    document.getElementById('nb_voyageurs').addEventListener('change', updateTotalPrice);
});

// Fonction pour changer l'image principale
function changeMainImage(src) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = src;

    // Mettre à jour la classe active sur les vignettes
    const thumbnails = document.querySelectorAll('.thumbnails img');
    thumbnails.forEach(thumb => {
        if (thumb.src.includes(src)) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
}

// Fonction pour contacter le propriétaire
function contacterProprietaire(idProprietaire) {
    // Rediriger vers la messagerie avec l'ID du propriétaire
    window.location.href = 'messagerie.php?contact=' + idProprietaire;
}

// Fonction pour basculer un logement en favoris
function toggleFavori(event, logementId) {
    event.stopPropagation(); // empêche d'autres événements

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

// Initialisation du calcul de prix basé sur les dates
function initDateCalculation() {
    const dateArrivee = document.getElementById('date_arrivee');
    const dateDepart = document.getElementById('date_depart');

    if (dateArrivee && dateDepart) {
        // Définir la date d'arrivée minimale à aujourd'hui
        const today = new Date().toISOString().split('T')[0];
        dateArrivee.min = today;

        // Mettre à jour la date minimale de départ lorsque la date d'arrivée change
        dateArrivee.addEventListener('change', function() {
            if (dateArrivee.value) {
                // Date de départ minimum = date d'arrivée + 1 jour
                const minDepartDate = new Date(dateArrivee.value);
                minDepartDate.setDate(minDepartDate.getDate() + 1);
                dateDepart.min = minDepartDate.toISOString().split('T')[0];

                // Si la date de départ est antérieure à la nouvelle date minimale, la réinitialiser
                if (dateDepart.value && new Date(dateDepart.value) < minDepartDate) {
                    dateDepart.value = dateDepart.min;
                }

                updateTotalPrice();
            }
        });

        // Mettre à jour le calcul du prix lorsque la date de départ change
        dateDepart.addEventListener('change', updateTotalPrice);
    }
}

// Mise à jour du prix total
function updateTotalPrice() {
    const dateArrivee = document.getElementById('date_arrivee');
    const dateDepart = document.getElementById('date_depart');
    const nbVoyageurs = document.getElementById('nb_voyageurs');
    const totalNightsElement = document.getElementById('total-nights');
    const totalPersonsElement = document.getElementById('total-persons');
    const prixTotalElement = document.getElementById('prix-total');

    // Vérifier que tous les éléments nécessaires sont présents
    if (!dateArrivee || !dateDepart || !nbVoyageurs || !totalNightsElement || !totalPersonsElement || !prixTotalElement) {
        return;
    }

    // Récupérer le prix par personne et par nuit
    const prixParPersonne = parseFloat(document.querySelector('.prix-nuit').textContent);

    // Calculer seulement si les deux dates sont sélectionnées
    if (dateArrivee.value && dateDepart.value) {
        // Calculer le nombre de nuits
        const arrivee = new Date(dateArrivee.value);
        const depart = new Date(dateDepart.value);
        const diffTime = Math.abs(depart - arrivee);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // Mettre à jour l'affichage du nombre de nuits
        totalNightsElement.querySelector('span:last-child').textContent = diffDays;

        // Mettre à jour l'affichage du nombre de personnes
        const personnes = parseInt(nbVoyageurs.value);
        totalPersonsElement.querySelector('span:last-child').textContent = personnes;

        // Calculer le prix total
        const prixTotal = prixParPersonne * diffDays * personnes;
        prixTotalElement.textContent = prixTotal.toFixed(2) + ' €';
    } else {
        // Si les dates ne sont pas encore sélectionnées
        totalNightsElement.querySelector('span:last-child').textContent = '-';
        prixTotalElement.textContent = '-';
    }
}

// Validation du formulaire avant soumission
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('booking-form');

    if (bookingForm) {
        bookingForm.addEventListener('submit', function(event) {
            const dateArrivee = document.getElementById('date_arrivee').value;
            const dateDepart = document.getElementById('date_depart').value;

            if (!dateArrivee || !dateDepart) {
                event.preventDefault();
                alert('Veuillez sélectionner les dates de votre séjour.');
                return false;
            }

            const arrivee = new Date(dateArrivee);
            const depart = new Date(dateDepart);

            if (arrivee >= depart) {
                event.preventDefault();
                alert('La date de départ doit être postérieure à la date d\'arrivée.');
                return false;
            }

            // Le formulaire peut être soumis
            return true;
        });
    }
});