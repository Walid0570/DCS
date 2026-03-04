#!/bin/bash

# Script pour convertir MySQL SQL en SQLite SQL
input_file="/workspaces/DCS/database/data.sql"
output_file="/workspaces/DCS/database/data_sqlite.sql"

# Supprimer les commandes MySQL incompatibles et convertir
sed \
  -e '/^SET SQL_MODE/d' \
  -e '/^START TRANSACTION/d' \
  -e '/^SET time_zone/d' \
  -e 's/`//g' \
  -e '/^!40101/d' \
  -e '/^COMMIT/d' \
  "$input_file" > "$output_file"

echo "✓ Fichier converti: $output_file"

# Créer la base SQLite
cd /workspaces/DCS/database
sqlite3 campus_it.db < "$output_file"
echo "✓ Base de données créée: campus_it.db"

# Afficher les tables
echo ""
echo "Tables disponibles:"
sqlite3 campus_it.db ".tables"
