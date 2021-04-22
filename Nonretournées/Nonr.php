<?php

include '../functions.php';
$pdo = pdo_connect_mysql();



  
  global $code;
  global $val;
  
  $statement2 = $pdo->prepare
        ('select e.dateFin,et.CNE,e.dateDebut,et.nomEtu,et.prenomEtu,et.CNI,l.titreLiv,d.libelleDis
          from emprunt e, livre l, etudiant et,exemplaire ex,discipline d
          where l.ISBN=ex.ISBN
          and l.codeDis=d.codeDis
          and ex.codeBar=e.codeBar
          and et.CBR=e.CBR
          and DATEDIFF(e.dateFin, e.dateDebut)>2;');
        $statement2->execute();
        $contacts2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['CNE'])) {
      // Select the record that is going to be deleted
      $stmt = $pdo->prepare('SELECT * FROM etudiant WHERE CNE = ?');
      $stmt->execute([$_GET['CNE']]);
      $contact = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$contact) {
          exit('étudiant nexites pas');
      }
      if(isset($_GET['libellePun'])){ 
        $smt = $pdo->prepare('select codePun from punition where libellePun = ?');
        $smt ->execute([$_GET['libellePun']]);
        $punit = $smt->fetch(PDO::FETCH_ASSOC);
        print_r($punit['codePun']);
      if (isset($_GET['confirm'])) {
          if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('INSERT IGNORE INTO punir VALUES (?, ?)');
            $stmt->execute([print_r($punit['codePun']),$_GET['CNE']]);
            header('Location: Nonr.php');
          }
      }
  }
}
?>

<?=template_header('Read')?>

<div class="container">   
            <div class="head">
              <h2>Emprunts non retournées (dépassement de 48 heures)</h2><br> 
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
                    <h3>
                    <?php
                          $smt = $pdo->prepare('select libellePun From punition');
                          $smt->execute();
                          $data = $smt->fetchAll();
                      ?>
                      <form action="Nonr.php" method="post">
                        <select name="disc" id="disc" style="padding:6px 25px 6px 25px;font-size:large">
                          <?php foreach ($data as $row): 
                            $val = $row["libellePun"];
                            ?>
                              <option><?=$row["libellePun"]?></option>
                          <?php endforeach ?>
                        </form>
                      </select>
                    </h3>
                    <div class="btn">
                        <a class="btnapp" id="show" style="cursor: pointer;"  href="Nonr.php?CNE=<?=$contact['CNE']?>&libellePun=<?= $val ?>&confirm=yes">
                            <i class="fas fa-check"></i>
                                Validation
                        </a>
                    </div>  
                  </div>
                </article>
            <?php endforeach; ?>      
        </div>        
    </div>
<?=template_footer()?>