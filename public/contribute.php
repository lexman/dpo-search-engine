<?php
include '../templates/templates.php';

$nom_organisme = $_POST['nom_organisme']; 
$site_organisme = $_POST['site_organisme']; 
$siren_organisme = $_POST['siren_organisme']; 
$dpo_mail = $_POST['dpo_mail']; 
$page_verification_dpo = $_POST['page_verification_dpo']; 
$organisme_sender_email = $_POST['organisme_sender_email']; 
$organisme_linkedin = $_POST['organisme_linkedin']; 
$organisme_contacts = $_POST['organisme_contacts']; 

if (strlen($nom_organisme) > 0 || strlen($site_organisme) > 0 || strlen($siren_organisme) > 0) {
  // Entrée correcte : au moins 1 des 3
  
  try{
    $db_path = 'private/data/contribute.db';
    $conn = new PDO("sqlite:$db_path");
  } catch(Exception $e) {
      echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
      die();
  }

  
  $stm = $conn->prepare("
    INSERT INTO contrib_dpo (
      nom_organisme,
      site_organisme,
      siren_organisme,
      dpo_mail, 
      page_verification_dpo,
      organisme_sender_email, 
      organisme_linkedin, 
      organisme_contacts 
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $params = array($nom_organisme, $site_organisme, $siren_organisme, $dpo_mail, $page_verification_dpo, $$organisme_sender_email, $organisme_linkedin, $organisme_contacts);
  
  if (! $stm->execute($params)) {
     print_r($conn->errorInfo());
    die("Erreur de base de données dans la soumission.");
  } 
}

include "private/templates/contribute.phtml";
?>