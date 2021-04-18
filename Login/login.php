<?php
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

$pdo = pdo_connect_mysql();
$msg='';

session_start();

if (!empty($_POST)) { 
    $codeb = $_POST['codeb'] ;
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $stmt0 = $pdo->prepare('SELECT * FROM gestionnaire ');
        $stmt0->execute();
        $codeA = $stmt0->fetchAll(PDO::FETCH_ASSOC);
        foreach($codeA as $code){
            if($codeb === $code['CBGest']){
                $msg = '';
                $stmt1 = $pdo->prepare('SELECT * FROM gestionnaire where CBGest = ? ');
                    $stmt1->execute([$codeb]);
                    $session = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                    foreach($session as $ses){
                        $_SESSION["code"]=$ses['CBGest'];
                        $_SESSION["nom"]=$ses['nomG'];
                        $_SESSION["prenom"]=$ses['prenomG'];
                    }
               header('Location:../Livres/indexL.php');
            }
            else{
                $msg='Code-barres incorrecte ';
            }
        }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>The Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="wrap">
        <form class="login-form" action="login.php" method="POST">
            <div class="form-header">
                <img src="../Images/log.jpg" width="150px" height="150px"> 
                <h3 style="font-size: larger;">Espace gestionnaire</h3>
                <p style="font-size:large;">Connectez-vous à votre espace</p>
            </div>
            <input type="password" name="codeb" class="form-input" placeholder="Scanner votre code-barres" style="margin-bottom: 10px;">
            <div class="error">
            <label ><?php echo $msg?></label>
            </div>
            <p style="text-align: center;font-size:1.1rem;">OU</p>
            <div class="form-footer" style="margin-top:10px;">     
                <div class="form-group">
                    <input name="email" type="text" class="form-input" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" name="mdp" class="form-input" placeholder="Mot de passe">
                </div>
                <div class="form-group">
                    <button class="form-button" type="submit">
                        Se connecter
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>