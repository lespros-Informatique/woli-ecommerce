
  // graph des mois de loyer payés
  function graphLoyerEncaise (jsTable,htmlId){
    // Définition des mois (Index de 0 à 11 pour Chart.js)
    const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

    // Données initialisées à 0 pour tous les mois
    const payeData = new Array(12).fill(0);
    const nonPayeData = new Array(12).fill(0);

    // Vérification des données et remplissage des tableaux
    if (Array.isArray(jsTable)) {
        jsTable.forEach(entry => {
            const monthIndex = parseInt(entry.mois, 10) - 1; // Convertit "01" en 0 (Janvier)
            payeData[monthIndex] = parseInt(entry.montant_total_paye, 10) || 0;
            nonPayeData[monthIndex] = parseInt(entry.locataires_non_payés, 10) || 0;
        });
    }

    // Sélection de l'élément du canvas pour Chart.js
    const ctx = document.getElementById(htmlId).getContext('2d');

    // Création du graphique avec Chart.js
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Loyers encaissés (fcfa)',
                    data: payeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Loyers non payés (Nbre de locataires)',
                    data: nonPayeData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true, // Rendu adaptatif
            maintainAspectRatio: false, // Permet d'ajuster la taille
            scales: {
                y: { beginAtZero: true }
            }
        }
  });
}

// graph des apparts occupés & libre
function graphAppartement (logementsOccupesParam,logementsVacantsParam,htmlId){

  // Données pour Chart.js
  const occupancyData = {
      labels: ['Appartements Occupés', 'Appartements Vacants'],
      datasets: [{
          data: [logementsOccupesParam, logementsVacantsParam],  
          backgroundColor: ['#4caf50', '#f44336'], // Vert pour occupés, rouge pour vacants
          borderColor: '#fff',
          borderWidth: 1
      }]
  };

  // Création du graphique
  new Chart(document.getElementById(htmlId).getContext('2d'), {
      type: 'pie',
      data: occupancyData,
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'top',
              }
          }
      }
  });
}
//   graph des revenus mensul
function graphRevenus (revenusTable,htmlId){
      // Données dynamiques venant de PHP

    // Labels des mois
    const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

    // Préparation des données
    const revenusMensuels = new Array(12).fill(0); // Initialisation avec 0

    revenusTable.forEach(entry => {
        const moisIndex = parseInt(entry.mois, 10) - 1; // Convertit "01" en index 0
        revenusMensuels[moisIndex] = parseInt(entry.montant_total_paye, 10) || 0;
    });

    // Sélection du canvas
    const ctx = document.getElementById(htmlId).getContext('2d');

    // Création du graphique
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenus encaissés (FCFA)',
                data: revenusMensuels,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                fill: true,
                tension: 0.3 // Courbe lissée
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString() + ' FCFA';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FCFA';
                        }
                    }
                }
            }
        }
    });

}
