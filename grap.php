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
    <?php
    // lire les données JSON générées par le script Python
    $jsonPath = __DIR__ . '/Structure/data.json';
    $projectData = file_exists($jsonPath) ? file_get_contents($jsonPath) : '{}';
    ?>
    <h1>Visualisations de consommation</h1>
    <section>
        <h2>1. Top 5 des applications</h2>
        <div id="chart-container1"><canvas id="chart1"></canvas></div>
    </section>
    <section>
        <h2>2. Evolution mensuelle globale</h2>
        <div id="chart-container2"><canvas id="chart2"></canvas></div>
    </section>
    <section>
        <h2>3. Comparaison stockage vs réseau</h2>
        <div id="chart-container3"><canvas id="chart3"></canvas></div>
    </section>

    <script>
        // JSON data disponible dans la variable projectData
        const projectData = <?php echo $projectData; ?>;
        document.addEventListener('DOMContentLoaded', function () {
            // charger les données JSON générées par le back-end
            const homeData = JSON.parse(projectData);
            // 1. Top applications
            const top = homeData.top_apps || [];
            const ctx1 = document.getElementById('chart1').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: top.map(a => a.nom),
                    datasets: [{
                        label: 'Consommation totale',
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1,
                        data: top.map(a => a.total)
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Volume' } } }
                }
            });
            // 2. Evolution mensuelle
            const monthly = homeData.monthly_totals || [];
            const ctx2 = document.getElementById('chart2').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: monthly.map(m => m.month),
                    datasets: [{
                        label: 'Consommation totale',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: true,
                        data: monthly.map(m => m.total)
                    }]
                },
                options: { responsive: true }
            });
            // 3. Ressource comparaison
            const cmp = homeData.resource_comparison || [];
            const ctx3 = document.getElementById('chart3').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: cmp.map(r => r.month),
                    datasets: [
                        {
                            label: 'Stockage',
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            data: cmp.map(r => r.stockage || 0)
                        },
                        {
                            label: 'Réseau',
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            data: cmp.map(r => r.reseau || 0)
                        }
                    ]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        });
    </script>

</body>
</html>
