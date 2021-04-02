<?php 
$q = $_GET['q']; 
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Contacter un Délégué aux Données Personnelles</title>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
        </style>
    </head>
    <body>
        <div class="container">
          <div class="text-center">
            <h1 class="display-3">Qui contacter pour mes données personnelles ?</h1>
            <p clas="lead">
              Vous êtes dans la base de données d'une entreprise ? Vous recevez du spam ? <br/>
              Trouvez le contact pour formuler vos demandes RGPD de droit à l'oubli parmi les
              Délégués à la Protection de Données <a href="https://www.data.gouv.fr/fr/datasets/organismes-ayant-designe-un-e-delegue-e-a-la-protection-des-donnees-dpd-dpo/">déclarées auprès de la CNIL</a>
            </p>
          </div>
          <p class="m-5">
            <form class="text-center" method="GET">
              <div class="custom-control-inline">
                <input type="text" class="form-control" name="q" value="<?= $q ?>" placeholder="sephora, donald, shop, etc.">
                <button type="submit" class="btn btn-primary">Go !</button>              
              </div>
            </form>
            <small>Conseil : vous pouvez chercher tout ou partie du nom, du site web ou de l'adresse mail de l'organisme. Vous pouvez même chercher par le numéro SIREN de l'entreprise, disponible les mentions légales ou dans les mails</small>
          </p>
      
<?php
function format_etablissement($nom, $adresse, $cp, $ville) {
  return "<span><strong>$nom</strong></span> <br/> <small><span>$adresse</span><br/><span>$cp $ville</span></small>";
}

function format_contact($mail, $url) {
  $links = array();
  if (strlen($mail) > 0) {
    $links[] = "<a href='mailto://$mail'>$mail</a>" ;
  }
  if (strlen($url) > 0) {
    $links[] = "<a href='$url' target='blank'>$url</a>" ;
  }
  return join("<br/>", $links);
}

function format_row($row) {
  $siren = $row['organisme_siren'];
  $organisme = format_etablissement($row['organisme_nom'], $row['organisme_adresse'], $row['organisme_cp'], $row['organisme_ville']);
  $dpo = format_etablissement($row['dpo_nom'], $row['dpo_adresse'], $row['dpo_cp'], $row['dpo_ville']);
  if ($row['type_dpo'] == "Personne morale") {
    $dpo .= "<br/><small>(Personne morale)</small>";
  }
  $contact = format_contact($row['contact_dpo_mail'], $row['contact_dpo_url']);
  $maj = $row['date_designation'];
  return "
  <tr>
      <th scope=\"row\">$siren</th>
      <td>$organisme</td>
      <td>$dpo</td>
      <td>$contact</td>
      <td>$maj</td>
  </tr>
  ";
}

if (strlen($q) > 0 ) {
    
  try{
    $db_path = __DIR__ . '/../data/data.db';
    $conn = new PDO("sqlite:$db_path");
  } catch(Exception $e) {
      echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
      die();
  }
  $like_term = $conn->quote("%$q%");

  $stm = $conn->query("
    SELECT *
    FROM organisme_dpo 
    WHERE 
         organisme_siren LIKE $like_term
      OR organisme_nom LIKE $like_term
      OR dpo_siren LIKE $like_term
      OR dpo_nom LIKE $like_term
      OR contact_dpo_mail LIKE $like_term
      OR contact_dpo_url LIKE $like_term
      OR contact_dpo_tel LIKE $like_term
  ");
  $rows = $stm->fetchAll();
  
  ?>     
          <br/>
          <table class="table  ">
            <thead class="thead-light">
              <tr>
                <th scope="col">SIREN</th>
                <th scope="col">Organisme</th>
                <th scope="col">DPO</th>
                <th scope="col">Contact DPO</th>
                <th scope="col">Mise-à-jour</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($rows as $row) {
                print(format_row($row));
              }
              ?>
            </tbody>
          </table>
          <p class="text-right">
          Source : liste des Délégués à la Protection de Données <a href="https://www.data.gouv.fr/fr/datasets/organismes-ayant-designe-un-e-delegue-e-a-la-protection-des-donnees-dpd-dpo/">déclarées auprès de la CNIL</a>.
          </p>
<?php 
  }
?>
        </div>
    </body>
</html>


