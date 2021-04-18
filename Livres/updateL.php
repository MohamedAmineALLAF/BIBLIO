<?php
include '../functions.php';
$pdo = pdo_connect_mysql();
$msg = ''; 
global $val1;
global $val2;

if (isset($_GET['ISBN'])) {
if (!empty($_POST)) { 
    $isbn = $_POST['isbn'] ;
    $titre = $_POST['titre'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $disc = $_POST['disc'];
    $nbxmp = $_POST['nbxmp'];

    $stmt = $pdo->prepare('UPDATE auteur a,rediger r
        SET a.nomAut = ?, a.prenomAut = ?
        WHERE a.codeAut = r.codeAut
        and r.ISBN = ?');
        $stmt->execute([$nom,$prenom,$_GET['ISBN']]);

    $stmt0 = $pdo->prepare('SELECT * FROM rediger where ISBN = ?');
        $stmt0->execute([$_GET['ISBN']]);
        $codeA = $stmt0->fetchAll(PDO::FETCH_ASSOC);
        foreach ($codeA as $contact){
            $val1 = $contact['codeAut'];
        }

    $stmt1 = $pdo->prepare('SELECT * FROM Discipline WHERE libelleDis LIKE :title');
            $stmt1->bindValue(':title',$disc);
            $stmt1->execute();
            $codeD = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            foreach ($codeD as $discipline){
            $val2 = $discipline['codeDis'];
        }

    $stmt2 = $pdo->prepare('UPDATE livre
        SET ISBN = ?, titreLiv = ?, nbExemplaire = ?,codeDis=?
        WHERE ISBN = ?');
        $stmt2->execute([$_GET['ISBN'], $titre, $nbxmp, $val2 ,$_GET['ISBN']]);
    
    $stmt3 = $pdo->prepare('UPDATE rediger
        SET ISBN = ?, codeAut=?
        WHERE ISBN = ?');
        $stmt3->execute([$_GET['ISBN'],$val1 ,$_GET['ISBN']]);
        $msg = 'Modifié avec succès !';
        header('Location: indexL.php');
}
    $stmt = $pdo->prepare
    ('SELECT l.ISBN,l.titreLiv,a.nomAut,a.prenomAut,d.libelleDis,l.nbExemplaire 
    FROM auteur a, rediger r, livre l,discipline d  
    WHERE a.codeAut=r.codeAut
    and r.ISBN=l.ISBN
    and d.codeDis=l.codeDis
    and l.ISBN = ?');
    $stmt->execute([$_GET['ISBN']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Gestionnaire nexistes pas');
    }
} 
?>

<?=template_header('Read')?>

<div class="content update">
	<h2 style="color: black;">Modifier livre : </h2>
    <form action="updateL.php?ISBN=<?=$contact['ISBN']?>" method="post">
    <div class="wrap">
        <div class="row1">
            <label for="id">ISBN</label>
            <input type="text" name="isbn" placeholder="Q11111" value="<?=$contact['ISBN']?>"  id="cb">
            <label for="id">Titre</label>
            <input type="text" name="titre" placeholder="Q11111" value="<?=$contact['titreLiv']?>" id="cin">
            <label for="name">Nom Auteur</label>
            <input type="text" name="nom" placeholder="John" value="<?=$contact['nomAut']?>" id="nom">
        </div>
       
        <div class="row2">
            <label for="title">Prénom auteur</label>
            <input type="text" name="prenom" placeholder="fake.fake@fake.com" value="<?=$contact['prenomAut']?>" id="email">
            <label for="title" >Discipline</label>
            <?php
                $smt = $pdo->prepare('select libelleDis From Discipline');
                $smt->execute();
                $data = $smt->fetchAll();
            ?>
            <select name="disc" id="disc" value="">
                <?php foreach ($data as $row): ?>
                    <option ><?=$row["libelleDis"]?></option>
                <?php endforeach ?>
            </select>
            <label for="title">Nombres d'exemplaires</label>
            <input type="number" name="nbxmp" placeholder="10" id="nbxmp" value="<?=$contact['nbExemplaire']?>" >
        </div>
        <input type="submit" value="Modifier" class="crt" style="letter-spacing: 1px;"> 
    </div>
    </form>
    <?php if ($msg): ?>
    <p style="color: black;"><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>