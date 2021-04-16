if [ ! -e ../www/private/data/data.db ]; then
  curl -o opencnil-organismes-avec-dpo.csv -L  https://www.data.gouv.fr/fr/datasets/r/c5d02b42-1008-4406-83f5-3a81c8b936a3
	sqlite3 -init create-dpo-database.sql -echo -batch -bail ../www/private/data/data.db ".exit"
else
	echo "../www/private/data/data.db already exists... Skipping creation"
fi
if [ ! -e ../www/private/data/contribute.db ]; then
	sqlite3 -init create-contrib-database.sql -echo -batch -bail ../www/private/data/contribute.db ".exit"
else
	echo "../www/private/data/contribute.db already exists... Skipping creation"
fi
