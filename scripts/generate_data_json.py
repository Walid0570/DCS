#!/usr/bin/env python3
import sqlite3
import json
from pathlib import Path

DB = Path(__file__).resolve().parents[1] / 'database' / 'campus_it.db'
OUT = Path(__file__).resolve().parents[1] / 'Structure' / 'data.json'

conn = sqlite3.connect(DB)
cur = conn.cursor()

# Top 5 applications by total consumption
cur.execute('''
SELECT a.app_id, a.nom, SUM(c.volume) as total
FROM consommation c
JOIN application a ON c.app_id = a.app_id
GROUP BY a.app_id
ORDER BY total DESC
LIMIT 5
''')
top = [{'app_id': r[0], 'nom': r[1], 'total': float(r[2] or 0)} for r in cur.fetchall()]

# Monthly totals Jan 2025 -> Feb 2026
cur.execute('''
SELECT substr(mois,1,7) as month, SUM(volume) as total
FROM consommation
WHERE mois >= '2025-01-01' AND mois <= '2026-02-28'
GROUP BY month
ORDER BY month
''')
monthly = [{'month': r[0], 'total': float(r[1] or 0)} for r in cur.fetchall()]

# Resource comparison (Stockage=res_id 1, Réseau=res_id 3) month by month Jan 2025 -> Feb 2026
cur.execute('''
SELECT substr(mois,1,7) as month, res_id, SUM(volume) as total
FROM consommation
WHERE mois >= '2025-01-01' AND mois <= '2026-02-28' AND res_id IN (1,3)
GROUP BY month, res_id
ORDER BY month, res_id
''')
rows = cur.fetchall()

cmp = {}
for month, res_id, total in rows:
    if month not in cmp:
        cmp[month] = {}
    if res_id == 1:
        cmp[month]['stockage'] = float(total or 0)
    elif res_id == 3:
        cmp[month]['reseau'] = float(total or 0)

resource_comparison = []
for m in sorted(cmp.keys()):
    resource_comparison.append({'month': m, 'stockage': cmp[m].get('stockage', 0.0), 'reseau': cmp[m].get('reseau', 0.0)})

out = {
    'top_apps': top,
    'monthly_totals': monthly,
    'resource_comparison': resource_comparison
}

OUT.parent.mkdir(parents=True, exist_ok=True)
OUT.write_text(json.dumps(out, indent=2, ensure_ascii=False))
print('Wrote', OUT)

conn.close()
