-- Création des tables pour SQLite

CREATE TABLE IF NOT EXISTS application (
  app_id INTEGER PRIMARY KEY,
  nom VARCHAR(80)
);

CREATE TABLE IF NOT EXISTS ressource (
  res_id INTEGER PRIMARY KEY,
  nom VARCHAR(50),
  unite VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS consommation (
  conso_id INTEGER PRIMARY KEY,
  app_id INTEGER,
  res_id INTEGER,
  mois DATE,
  volume REAL,
  FOREIGN KEY(app_id) REFERENCES application(app_id),
  FOREIGN KEY(res_id) REFERENCES ressource(res_id)
);

