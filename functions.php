<?php

session_start();

function pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'biblio';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	exit('Failed to connect to database!');
    }
}






function template_header($title) {
  $nom = $_SESSION["nom"];
  $prenom = $_SESSION["prenom"];
echo <<<EOT
<!DOCTYPE html>
<html>
    <head>
      <link rel="stylesheet" href="../nav.css">
      <link rel="stylesheet" href="../Livres/indexL.css">
      <link rel="stylesheet" href="../Livres/createL.css">
      <link rel="stylesheet" href="../Gestionnaire/deleteG.css">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
      <script src="../script.js" defer></script>
      <script src="../JsBarcode.all.min.js"></script>
      <script src="../jquery.js"></script>
      <title>Responsive Navbar</title>
    </head>
    <body>
      <nav class="navbar">
        <div class="brand-title">FS EL JADIDA UCD</div>
        <a href="#" class="toggle-button">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </a>
        <div class="navbar-links">
          <ul>
            <li><a href="#">Statistiques</a></li>
            <li><a href="../Gestionnaire/indexG.php">Gestionnaires</a></li>
            <li><a href="../Livres/indexL.php">Livres</a></li>
            <li><a href="#">Emprunteurs</a></li>
            <li><a href="#">Emprunts non retournées</a></li>
          </ul>
        </div>
        <div class="navbar-links ">
          <div class="dropdown">    
            <ul>
                <li> 
                    <a href="">  $nom $prenom   
                      <i class="fas fa-caret-square-down"></i>
                    </a>
                </li>
            </ul>
            <div class="dropdown-content">
                <a href="../Gestionnaire/profilG.php">Profil</a>
                <a href="../Login/logout.php">Déconnexion</a>
            </div>
          </div>
        </div>
      </nav>
      <main>
      <div class="container">
EOT;
}
function template_footer() {
echo <<<EOT
</div>
</main>
    </body>
    <script>       
    const toggleButton = document.getElementsByClassName('toggle-button')[0]
    const navbarLinks = document.getElementsByClassName('navbar-links')[0]
    const navbarLinks2 = document.getElementsByClassName('navbar-links')[1]
    toggleButton.addEventListener('click', () => {
    navbarLinks.classList.toggle('active');
    navbarLinks2.classList.toggle('active');
    })

    </script>
</html>
EOT;
}
?>