curl -o opencnil-organismes-avec-dpo.csv -L  https://www.data.gouv.fr/fr/datasets/r/c5d02b42-1008-4406-83f5-3a81c8b936a3
sqlite3 -init create-database.sql -echo -batch -bail data.db ".exit"
