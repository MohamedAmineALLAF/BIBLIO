<?php

include '../functions.php';
$pdo = pdo_connect_mysql();



$stmt1 = $pdo->prepare('select * from emprunt where etat = 1');
$stmt1 -> execute();
$emprunts1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare('select * from emprunt where etat = 0');
$stmt2 -> execute();
$emprunts2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

global $value;
$stmt2 = $pdo->prepare('select CBGest from gestionnaire where nomG = ? and prenomG = ?');
$stmt2 -> execute([$_SESSION['nom'],$_SESSION['prenom']]);
$gest = $stmt2->fetchAll(PDO::FETCH_ASSOC);
foreach($gest as $g){
    $value = $g['CBGest'];
}


  $statement2 = $pdo->prepare
        ('select  e.codeEmprunt,e.dateDebut,e.dateFin,l.titreLiv,et.CNI,et.nomEtu,et.prenomEtu,d.libelleDis
        from emprunt e,livre l,exemplaire ex,etudiant et,discipline d
        where l.ISBN=ex.ISBN
        and d.codeDis=l.codeDis
        and ex.codeBar = e.codeBar 
        and	e.CBR=et.CBR
        and e.etat = 0
        order by e.dateDebut desc;');
    $statement2->execute();
    $contacts2 = $statement2->fetchAll(PDO::FETCH_ASSOC);


    if (isset($_GET['codeEmprunt'])) {
      
      // Select the record that is going to be deleted
      $stmt = $pdo->prepare('SELECT * FROM emprunt WHERE codeEmprunt = ?');
      $stmt->execute([$_GET['codeEmprunt']]);
      $contact = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$contact) {
          exit('emprunt nexites pas');
      }
      if (isset($_GET['confirm'])) {
          if ($_GET['confirm'] == 'yes') {
              $exemp = $_POST['exemp'];
              $stmt = $pdo->prepare('update emprunt
              set Etat = 1
              ,codeBar = ?
              ,CBGest = ?
              WHERE codeEmprunt = ?');
              $stmt->execute([$exemp,$value,$_GET['codeEmprunt']]);
              header('Location:indexEm.php');
          } else {
             $stmt = $pdo->prepare('delete from emprunt
              WHERE codeEmprunt = ?');
              $stmt->execute([$_GET['codeEmprunt']]);
              header('Location: indexEm.php');
              exit;
          }
      }
  }
?>

<?=template_header('Read')?>

<div class="container">   
            <div class="head">
              <h2>Emprunts en cours </h2><br> 
            </div>
            <div class="grid">
            <?php foreach ($contacts2 as $contact):?>
                <article>
                    <div class="text">
                    <h3 >Titre Livre : 
                      <?= $contact['titreLiv'] ?>
                    </h3>
                    <h3>
                        Catégorie livre  :  
                        <?= $contact['libelleDis'] ?>
                    </h3>
                    <h3>
                        Nom étudiant  :  
                        <?= $contact['nomEtu'] ?>
                    </h3>
                    <h3>
                        Prénom étudiant  :  
                        <?= $contact['prenomEtu'] ?>
                    </h3>
                    <h3>
                        CIN : 
                        <?= $contact['CNI'] ?>
                    </h3>
                    <h3>
                        Date début : 
                        <?= $contact['dateDebut'] ?>
                    </h3>
                    <h3>
                        Date fin : 
                        <?= $contact['dateFin'] ?>
                    </h3>
                    <div class="btn">
                        <a class="btnapp shw"  style="cursor: pointer;">
                            <i class="fas fa-check"></i>
                                Confirmer
                            </a>
                        <a class="btndelete" href="indexEm.php?codeEmprunt=<?=$contact['codeEmprunt']?>&confirm=no">
                            <i class="fas fa-times"></i>
                                Rejeter
                            </a>
                    </div>
                    <div class="hide" style="display: none;">
                    <form action="indexEm.php" method="post">
                       <input id="exe" type="text" class="search" name="exemp" style="width: 86%;margin:17px 0 17px;" placeholder="Scanner le code-barres de l'exemplaire"><br>
                       <a class="btnapp del" style="width: 300px;padding:8px;" href="indexEm.php?codeEmprunt=<?=$contact['codeEmprunt']?>&confirm=yes">
                          Validation
                        </a>
                      </form> 
                    </div>
                  </div>
                </article>
            <?php endforeach; ?>      
        </div>
        <h2 style="margin-top: 50px;">Emprunts retournés </h2>
        <div class="wrap">
              <form action="" method="post">

              </form>
        </div>
        <h2 style="margin-top: 50px;">Listes des emprunts </h2>
        <form>
              <div class="cont" style="margin-top: 20px;margin-bottom:15px;">
              <input type="text" class="search" name="search2"
               placeholder="Scanner ISBN du livre, code-barres "
               value="<?php  ?>">
                    <button type="submit" class="btnsearch">
                        <i class="fas fa-search">
                        </i>
                     </button>
              </div>
        </form>
        <form class="form-inline" method="post" action="../PDF/FICHIERS/PDFE.php">
              <button type="submit" id="pdf" name="generate_pdf" style="text-align: center;font-size:large" >
              <i style="color: rgb(250, 49, 49);" class="fas fa-file-pdf"></i>
               exporter PDF
              </button>
            </form>
            <div style="overflow-x: auto;overflow-y:auto;">
        <table>
        <thead>
            <tr>              
                <td>Titre livre</td>
                <td>Catégorie</td>
                <td>Etudiant</td>
                <td>CIN</td>
                <td>Date début</td>
                <td>Date fin</td>
            </tr>
        </thead>
        <tbody>

              <?php
              $search2 = $_GET['search2']??'';
              if($search2){
                  $smt = $pdo->prepare
                  ('select  e.codeEmprunt,e.dateDebut,e.dateFin,l.titreLiv,et.CNI,et.nomEtu,et.prenomEtu,d.libelleDis
                  from emprunt e,livre l,exemplaire ex,etudiant et,discipline d
                  where l.ISBN=ex.ISBN
                  and d.codeDis=l.codeDis
                  and ex.codeBar = e.codeBar 
                  and	e.CBR=et.CBR
                  and e.Etat = 1
                  and e.codeEmprunt= :title;');
                  $smt->bindValue(':title',"%$search2%");
              }else{
                $smt = $pdo->prepare
                      ('select  e.codeEmprunt,e.dateDebut,e.dateFin,l.titreLiv,et.CNI,et.nomEtu,et.prenomEtu,d.libelleDis
                      from emprunt e,livre l,exemplaire ex,etudiant et,discipline d
                      where l.ISBN=ex.ISBN
                      and d.codeDis=l.codeDis
                      and ex.codeBar = e.codeBar 
                      and	e.CBR=et.CBR
                      and e.Etat = 1
                      order by e.codeEmprunt desc;');
              }
                $smt->execute();
                $ctts = $smt->fetchAll(PDO::FETCH_ASSOC);
              ?>
            <?php foreach ($ctts as $contact):?>
            <tr>
                <td>
                    <?= $contact['titreLiv'] ?>
                </td>
                <td><?= $contact['libelleDis'] ?></td>
                <td><?php echo $contact['nomEtu'] ." ".$contact['prenomEtu'] ?></td>
                <td><?= $contact['CNI'] ?></td>
                <td><?=$contact['dateDebut']?></td>
                <td><?=$contact['dateFin']?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
              <td colspan="6">
              <?php
                global $val1;
                $stmt1 = $pdo->prepare('select count(*) as nombre from emprunt where etat = 1');
                $stmt1 -> execute();
                $emprunts1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                foreach($emprunts1 as $emprunts){
                  $val1 = $emprunts['nombre'];
                }
                ?>
                Le nombre total des emprunts validés est <?php echo $val1; ?>
              </td>
            </tr>
          </tbody>
        </table>
            </div>
    </div>
<?=template_footer()?>