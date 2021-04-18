<?php
include '../functions.php';
$pdo = pdo_connect_mysql();
$msg = ''; 
global $val1;
global $val2;

if (!empty($_POST)) {
$isbn = isset($_POST['isbn']) && !empty($_POST['isbn']) && $_POST['isbn'] != 'auto' ? $_POST['isbn'] : NULL;
$titre = isset($_POST['titre']) ? $_POST['titre'] : '';
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$disc = isset($_POST['disc']) ? $_POST['disc'] : '';
$nbxmp = isset($_POST['nbxmp']) ? $_POST['nbxmp'] : '';
$stmt = $pdo->prepare('INSERT INTO Auteur VALUES ( default, ?, ?)');
$stmt->execute([$nom, $prenom]);

$statement = $pdo->prepare('SELECT * FROM Auteur ORDER BY codeAut DESC LIMIT 1');
$statement->execute();
$codeA = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($codeA as $contact){
    $val1 = $contact['codeAut'];
}

$statement2 = $pdo->prepare('SELECT * FROM Discipline WHERE libelleDis LIKE :title');
$statement2->bindValue(':title',$disc);
$statement2->execute();
$codeD = $statement2->fetchAll(PDO::FETCH_ASSOC);
foreach ($codeD as $discipline){
    $val2 = $discipline['codeDis'];
}

$stmt = $pdo->prepare('INSERT INTO Livre VALUES (?, ?, ?, ?)');
$stmt->execute([$isbn, $titre, $nbxmp, $val2]);
$stmt = $pdo->prepare('INSERT INTO rediger VALUES (?, ?)');
$stmt->execute([$isbn,$val1]);
$msg = 'Ajouté avec succès !!';
header("Location:indexL.php");
}
?>
<?=template_header('Create')?>


<div class="content update">
<h2 style="color: black;">Ajouter Livre : </h2>
<form action="createL.php" method="post">
<div class="wrap">
    <div class="row1">
        <label for="id">ISBN </label>
        <input type="text" name="isbn" placeholder="XXXXXXXXXXXXX"  id="isbn">
        <label for="id">Titre</label>
        <input type="text" name="titre" placeholder="Software Engineering"  id="titre">
        <label for="name">Nom auteur</label>
        <input type="text" name="nom" placeholder="John " id="nom">
    </div>
    <div class="row2">
        <label for="email">Prénom auteur</label>
        <input type="text" name="prenom" placeholder="doe" id="prenom">
        <label for="title" >Discipline</label>
        <?php
            $smt = $pdo->prepare('select libelleDis From Discipline');
            $smt->execute();
            $data = $smt->fetchAll();
        ?>
        <select name="disc" id="disc">
            <?php foreach ($data as $row): ?>
                <option><?=$row["libelleDis"]?></option>
            <?php endforeach ?>
        </select>
        <label for="title">Nombres d'exemplaires</label>
        <input type="number" name="nbxmp" placeholder="10" id="nbxmp">
    </div>
    <input type="submit" value="Ajouter" class="crt" style="letter-spacing: 1px;"> 
</div>
</form>
<?php if ($msg): ?>
<p style="color: black;"><?=$msg?></p>
<?php endif; ?>
</div>

<?=template_footer()?>