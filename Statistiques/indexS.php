
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
        <h1>Statistiques générales : </h1>
        
        <div class="main_overview">     
          <div class="overview_card">
          <?php
            global $val;
            $stmt=$pdo->prepare('select count(l.ISBN) as nblivres 
            from livre l,exemplaire ex,emprunt e
            where l.ISBN=ex.ISBN
            and e.codeBar=ex.codeBar
            and DAY(e.dateDebut) = DAY(CURDATE())
            and YEAR(e.dateDebut) = YEAR(CURDATE());');
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($records as $record) {
                $val = $record['nblivres'];
            }
            ?>
            <div class="overview_card-info" >  <?= $val ?>  </div>
            <div class="overview_card-icon">livres empruntés aujourd'hui</div>
          </div>
          <?php
            global $val1;
            $stmt=$pdo->prepare('select count(l.ISBN) as nblivres 
            from livre l,exemplaire ex,emprunt e
            where l.ISBN=ex.ISBN
            and e.codeBar=ex.codeBar
            and YEARWEEK(e.dateDebut) = YEARWEEK(CURDATE());');
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($records as $record) {
                $val1 = $record['nblivres'];
            }
            ?>
          <div class="overview_card">
            <div class="overview_card-info"> <?= $val1 ?> </div>
            <div class="overview_card-icon">livres empruntés cette semaine</div>
          </div>
          <div class="overview_card">
          <?php
            global $val2;
            $stmt=$pdo->prepare('select count(l.ISBN) as nblivres 
            from livre l,exemplaire ex,emprunt e
            where l.ISBN=ex.ISBN
            and e.codeBar=ex.codeBar
            and MONTH(e.dateDebut) = MONTH(CURDATE())
            and YEAR(e.dateDebut) = YEAR(CURDATE());;');
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($records as $record) {
                $val2 = $record['nblivres'];
            }
            ?>
            <div class="overview_card-info"> <?= $val2 ?> </div>
            <div class="overview_card-icon">livres empruntés ce mois</div>
          </div>
          <div class="overview_card">
          <?php
            global $val2;
            $stmt=$pdo->prepare('select count(l.ISBN) as nblivres 
            from livre l,exemplaire ex,emprunt e
            where l.ISBN=ex.ISBN
            and e.codeBar=ex.codeBar
            and YEAR(e.dateDebut) = YEAR(CURDATE());;');
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($records as $record) {
                $val2 = $record['nblivres'];
            }
            ?>
            <div class="overview_card-info"> <?= $val2 ?> </div>
            <div class="overview_card-icon">livres empruntés cette année</div>
          </div>
          <div class="overview_card" style="background-color: #1b363f;color: white;">
            <?php
            global $val3;
            $stmt=$pdo->prepare('select count(l.ISBN) as nblivres 
            from livre l,exemplaire ex,emprunt e
            where l.ISBN=ex.ISBN
            and e.codeBar=ex.codeBar;');
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($records as $record) {
                $val3 = $record['nblivres'];
            }
            ?>
            <div class="overview_card-info" "> <?= $val3 ?> </div>
            <div class="overview_card-icon">Total de livres empruntés</div>
          </div>
        </div>
</div>
<h1>Actualités du jour : </h1>

<div class="wrap1">
        <?php
        
        $stmt=$pdo->prepare('select l.titreLiv ,et.nomEtu,et.prenomEtu,d.libelleDis,e.dateDebut
        from livre l,discipline d,exemplaire ex,emprunt e,etudiant et
        where l.ISBN=ex.ISBN
        and d.codeDis=l.codeDis
        and e.CBR=et.CBR
        and e.codeBar=ex.codeBar
        and DAY(e.dateDebut) = DAY(CURDATE())
        and YEAR(e.dateDebut) = YEAR(CURDATE());');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

    <div class="card">Les livres empruntés aujourd'hui: 
            <ol class="book-list">
            <?php foreach ($records as $emprunt): ?>
              <li> <strong>Titre : </strong> <?= $emprunt['titreLiv']?> <br> 
              <strong>Nom étudiant :   </strong><?= $emprunt['nomEtu']?><br>
              <strong>Prénom étudiant :   </strong><?= $emprunt['prenomEtu']?><br>
              <strong> Discipline : </strong><?= $emprunt['libelleDis']?><br>
              <strong>Date début :   </strong><?= $emprunt['dateDebut']?><br>
            </li>
            <?php endforeach; ?>
          </ol>
        </div>


        <?php
        
        $stmt=$pdo->prepare('select l.titreLiv ,et.nomEtu,et.prenomEtu,d.libelleDis,e.dateDebut
        from livre l,discipline d,exemplaire ex,emprunt e,etudiant et
        where l.ISBN=ex.ISBN
        and d.codeDis=l.codeDis
        and e.CBR=et.CBR
        and e.codeBar=ex.codeBar
        and DAY(e.dateFin) = DAY(CURDATE())
        and YEAR(e.dateFin) = YEAR(CURDATE());');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

    <div class="card">Les livres a retournés aujourd'hui: 
            <ol class="book-list">
            <?php foreach ($records as $emprunt): ?>
              <li> <strong>Titre : </strong> <?= $emprunt['titreLiv']?> <br> 
              <strong>Nom étudiant :   </strong><?= $emprunt['nomEtu']?><br>
              <strong>Prénom étudiant :   </strong><?= $emprunt['prenomEtu']?><br>
              <strong> Discipline : </strong><?= $emprunt['libelleDis']?><br>
              <strong>Date début :   </strong><?= $emprunt['dateDebut']?><br>
            </li>
            <?php endforeach; ?>
          </ol>
        </div>

</div>
<h1 >à-propos des livres : </h1>
<div class="wrap1">
    <div class="row11">
        <?php
        global $val;
        $stmt=$pdo->prepare('select l.titreLiv,count(*) as nombres
        from livre l,exemplaire ex,emprunt e
        where l.ISBN=ex.ISBN
        and ex.codeBar = e.codeBar
        group by l.titreLiv
        order by count(*) desc
        LIMIT 4;');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h3 style="text-align: center;">Les livres les<span style="color: #1DB954;"> plus </span> empruntés : </h3>
        <table >
            <tbody>
                <tr>
                    <td>Livres</td>
                    <td>Nombres d'emprunts</td>
                </tr>
            <?php foreach ($records as $record):?>
                <tr>
                    <td><?= $record['titreLiv'] ?></td>
                    <td><?= $record['nombres'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="row22">
    <?php
        global $val;
        $stmt=$pdo->prepare('select l.titreLiv,count(*) as nombres
        from livre l,exemplaire ex,emprunt e
        where l.ISBN=ex.ISBN
        and ex.codeBar = e.codeBar
        group by l.titreLiv
        order by count(*) asc
        LIMIT 4;');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h3 style="text-align: center;">Les livres les <span style="color: red;"> moins </span> empruntés : </h3>
        <table >
            <tbody>
                <tr>
                    <td>Livres</td>
                    <td>Nombres d'emprunts</td>
                </tr>
            <?php foreach ($records as $record):?>
                <tr>
                    <td><?= $record['titreLiv'] ?></td>
                    <td><?= $record['nombres'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<h1  >à-propos des disciplines : </h1>
<div class="wrap1">
    <div class="row11">
        <?php
        global $val;
        $stmt=$pdo->prepare('
        select DISTINCT d.libelleDis
        from livre l,exemplaire ex,emprunt e,discipline d
        where l.ISBN=ex.ISBN
        and ex.codeBar = e.codeBar
        AND d.codeDis = l.codeDis
        group by l.titreLiv
        order by count(*) desc
        LIMIT 4;');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
         <h3 style="text-align: center;">Les disciplines les<span style="color: #1DB954;"> plus </span> empruntés : </h3>
        <table >
            <tbody>
                <tr>
                    <td>Discipline</td>
                </tr>
            <?php foreach ($records as $record):?>
                <tr>
                    <td><?= $record['libelleDis'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="row22">
    <?php
        global $val;
        $stmt=$pdo->prepare('
        select DISTINCT d.libelleDis
        from livre l,exemplaire ex,emprunt e,discipline d
        where l.ISBN=ex.ISBN
        and ex.codeBar = e.codeBar
        AND d.codeDis = l.codeDis
        group by l.titreLiv
        order by count(*) asc
        LIMIT 4;');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h3 style="text-align: center;">Les livres les <span style="color: red;"> moins </span> empruntés : </h3>
        <table >
            <tbody>
                <tr>
                    <td>Discipline</td>
                </tr>
            <?php foreach ($records as $record):?>
                <tr>
                    <td><?= $record['libelleDis'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?=template_footer()?>