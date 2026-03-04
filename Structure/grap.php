<!DOCTYPE html>
<html>
<head>
    <title>Graphique de consommation mensuelle - Campus IT</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 { text-align: center; }
        #chart-container {
            width: 100%;
            height: auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Consommation mensuelle du campus</h1>
    <!-- on crée un canvas pour y afficher le graphique -->
    <div id="chart-container">
        <canvas id="graphCanvas"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // charger les données JSON générées par le back-end
            fetch('Structure/data.json')
                .then(resp => resp.json())
                .then(homeData => {
                    // utilisation des totaux mensuels
                    const monthly = homeData.monthly_totals;
                    const labels = monthly.map(m => m.month);
                    const values = monthly.map(m => m.total);

                    const ctx = document.getElementById('graphCanvas').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Consommation totale (toutes ressources)',
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                data: values
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Volume' }
                                },
                                x: {
                                    title: { display: true, text: 'Mois' }
                                }
                            }
                        }
                    });
                })
                .catch(err => console.error('Impossible de charger data.json :', err));
        });
    </script>

</body>
</html>
