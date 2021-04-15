<?php 
$q = $_GET['q']; 

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
                       

if (strlen($q) > 0 ) {
    
  try{
    $db_path = 'private/data/data.db';
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
}

include "private/templates/index.phtml";
?>