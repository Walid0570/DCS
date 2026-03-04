function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

function createTable(headers, rows) {
  const table = document.createElement('table');
  const thead = document.createElement('thead');
  const tr = document.createElement('tr');
  headers.forEach(h => {
    const th = document.createElement('th');
    th.textContent = h;
    tr.appendChild(th);
  });
  thead.appendChild(tr);
  table.appendChild(thead);
  const tbody = document.createElement('tbody');
  rows.forEach(r => {
    const trr = document.createElement('tr');
    r.forEach(cell => {
      const td = document.createElement('td');
      td.textContent = cell;
      trr.appendChild(td);
    });
    tbody.appendChild(trr);
  });
  table.appendChild(tbody);
  return table;
}

function renderTopApps(data) {
  const container = document.getElementById('top-apps-table');
  container.innerHTML = '';
  const headers = ['Rang', 'Application', 'Consommation totale'];
  const rows = data.top_apps.map((a, i) => [i+1, a.nom, a.total.toFixed(2)]);
  container.appendChild(createTable(headers, rows));
}

function renderMonthlyEvolution(data) {
  const container = document.getElementById('monthly-evolution-table');
  container.innerHTML = '';
  const headers = ['Mois', 'Consommation totale'];
  const rows = data.monthly_totals.map(m => [m.month, m.total.toFixed(2)]);
  container.appendChild(createTable(headers, rows));
}

function renderResourceComparison(data) {
  const container = document.getElementById('resource-comparison-table');
  container.innerHTML = '';
  const headers = ['Mois', 'Stockage (Go)', 'Réseau (Go)'];
  const rows = data.resource_comparison.map(r => [r.month, (r.stockage||0).toFixed(2), (r.reseau||0).toFixed(2)]);
  container.appendChild(createTable(headers, rows));
}

document.addEventListener('DOMContentLoaded', () => {
  // ouvrir le premier onglet
  const firstBtn = document.querySelector('.tablinks');
  if (firstBtn) firstBtn.click();

  fetch('data.json')
    .then(resp => resp.json())
    .then(data => {
      renderTopApps(data);
      renderMonthlyEvolution(data);
      renderResourceComparison(data);
    })
    .catch(err => console.error('Impossible de charger data.json', err));
});
