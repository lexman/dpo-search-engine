.headers on


DROP TABLE IF EXISTS organisme_dpo;

CREATE TABLE organisme_dpo (
  organisme_siren	,
  organisme_nom	,
  onisme_secteur_activite	,
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

.separator ";"

.import opencnil-organismes-avec-dpo.csv organisme_dpo

SELECT COUNT(*) FROM organisme_dpo;

SELECT *  FROM organisme_dpo WHERE organisme_nom LIKE "%n%" LIMIT 10;

SELECT COUNT(*)  FROM organisme_dpo WHERE organisme_nom LIKE "%n%" ;

CREATE VIRTUAL TABLE dpo_search USING fts4(title, keywords, body);
