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
            <h1>Qui contacter pour mes données personnelles ?</h1>
            <p clas="lead">
              Vous êtes dans un fichier client ? Vous voulez faire valoir votre droit à l'oubli ?<br/>
              Cherchez le contact <a href="https://www.data.gouv.fr/fr/datasets/organismes-ayant-designe-un-e-delegue-e-a-la-protection-des-donnees-dpd-dpo/">déclaré auprès la CNIL</a>
            </p>
          </div>
          <p class="m-5">
            <form class="text-center" method="GET">
              <div class="row">
                <input type="text" class="form-control col-md-6 m-2" name="q" value="<?= $q ?>" placeholder="sephora, donald, shop, etc.">
                <button type="submit" class="btn btn-primary col-md-2 m-2  ">Chercher !</button>              
              </div>
            </form>
            <small>Conseil : vous pouvez chercher tout ou partie du nom, du site web ou de l'adresse mail de l'organisme. <br/>
              Vous pouvez même chercher par le <a href="https://www.economie.gouv.fr/entreprises/numeros-identification-entreprise#numerosiren">numéro SIREN de l'entreprise</a>, souvent disponible les mentions légales des sites web ou en bas des mails promotionnels</small>
          </p>
      
<?php
  
function figures($str) {
  $result = "";
  $len = strlen($str);
  for($i = 0; $i < $len; $i++) {
      $ch = $str[$i];
      if ( ($ch >= '0')  && ($ch <= '9')) {
          $result .= $ch;
      }
  }
  return $result;
}
                       
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
  
  $siren_candidate = figures($q);
  if (strlen($siren_candidate) == 9) {
      // La requete ressemble à un numéro SIREN
      $is_siren_term = "organisme_siren = '$siren_candidate' OR
                        dpo_siren = '$siren_candidate' OR" ;
  } else {
      $is_siren_term = "" ;
  }
  
  $stm = $conn->query("
    SELECT *
    FROM organisme_dpo 
    WHERE 
         $is_siren_term
         organisme_nom LIKE $like_term
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


