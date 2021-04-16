.headers on

DROP TABLE IF EXISTS organisme_dpo;

CREATE TABLE organisme_dpo (
  organisme_siren	,
  organisme_nom	,
  organisme_secteur_activite	,
  organisme_naf	,
  organisme_adresse	,
  organisme_cp	,
  organisme_ville	,
  organisme_pays	,
  type_dpo	,
  date_designation	,
  dpo_siren	,
  dpo_nom	,
  dpo_secteur_activite	,
  dpo_naf	,
  dpo_adresse	,
  dpo_cp	,
  dpo_ville	,
  dpo_pays	,
  contact_dpo_mail	,
  contact_dpo_url	,
  contact_dpo_tel	,
  contact_dpo_adresse	,
  contact_dpo_cp	,
  contact_dpo_ville	,
  contact_dpo_pays	,
  contact_dpo_autre	,
  vide
)
;

.mode ascii
.separator ";" "\n"
.import opencnil-organismes-avec-dpo.csv organisme_dpo
-- remove header
DELETE FROM organisme_dpo WHERE rowid=1;
-- remove bad input lines if necessary
DELETE FROM organisme_dpo WHERE organisme_siren IS NULL;
.mode csv
SELECT * FROM organisme_dpo LIMIT 2;
SELECT COUNT(*) FROM organisme_dpo;

SELECT * FROM organisme_dpo WHERE organisme_nom LIKE "%n%" LIMIT 10;

SELECT COUNT(*)  FROM organisme_dpo WHERE organisme_nom LIKE "%n%" ;

