
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
        <h3 style="text-align: center;">Les livres les plus empruntés : </h3>
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