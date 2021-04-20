
<?php
include '../functions.php';
$pdo = pdo_connect_mysql();

$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
?>

<?=template_header('Read')?>

<div class="main">
        <h1 style="font-family: myFont;color: black;margin-left: 10px;">Bonjour <?php echo $_SESSION["nom"]." ".$_SESSION["prenom"] ?>,</h1>
        <p style="font-family: myFont;color: black;margin-left: 10px;">Bienvenue à votre tableau de bord, voici la liste des statistiques. </p>
        <div class="main_overview">     
          <div class="overview_card">
            <div class="overview_card-info" >3</div>
            <div class="overview_card-icon">livres empruntés aujourd'hui</div>
          </div>
          <div class="overview_card">
            <div class="overview_card-info">4</div>
            <div class="overview_card-icon">livres empruntés cette semaine</div>
          </div>
          <div class="overview_card">
            <div class="overview_card-info">123</div>
            <div class="overview_card-icon">livres empruntés ce mois</div>
          </div>
          <div class="overview_card" style="background-color: #1b363f;color: white;">
            <div class="overview_card-info" ">12345678</div>
            <div class="overview_card-icon">Total de livres empruntés</div>
          </div>
        </div>
        <div class="main_overview1">     
          <div class="overview_card1">
            <div class="overview_card-info1" >3</div>
            <div class="overview_card-icon1">livres empruntés aujourd'hui</div>
          </div>
          <div class="overview_card1">
            <div class="overview_card-info1">4</div>
            <div class="overview_card-icon1">livres empruntés cette semaine</div>
          </div>
          <div class="overview_card1">
            <div class="overview_card-info1">123</div>
            <div class="overview_card-icon1">livres empruntés ce mois</div>
          </div>
          <div class="overview_card1">
            <div class="overview_card-info1" ">12345678</div>
            <div class="overview_card-icon1">Total de livres empruntés</div>
          </div>
        </div>
        
</div>

<?=template_footer()?>