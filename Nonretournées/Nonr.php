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


$search2 = $_GET['search2']??'';
$exemp = $_GET['exemp']??'';

  $statement2 = $pdo->prepare
        ('select  e.codeEmprunt,e.dateDebut,e.dateFin,l.titreLiv,et.CNI,et.nomEtu,et.prenomEtu,d.libelleDis
        from emprunt e,livre l,exemplaire ex,etudiant et,discipline d
        where l.ISBN=ex.ISBN
        and d.codeDis=l.codeDis
        and ex.codeBar = e.codeBar 
        and	e.CBR=et.CBR
        and e.etat = 0
        order by e.dateDebut desc;');
        #select * from emprunt where DATEDIFF(dateFin, dateDebut)>2;
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
              $stmt = $pdo->prepare('update emprunt
              set Etat = 1
              and codeBar = ?
              and CBGest = ?
              WHERE codeEmprunt = ?');
              $stmt->execute([$exemp,$value,$_GET['codeEmprunt']]);
              header('Location: indexEm.php');
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
              <h2>Emprunts </h2><br> 
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
                        <a class="btnapp" id="show" style="cursor: pointer;">
                            <i class="fas fa-check"></i>
                                Confirmer
                            </a>
                        <a class="btndelete" href="indexEm.php?codeEmprunt=<?=$contact['codeEmprunt']?>&confirm=no">
                            <i class="fas fa-times"></i>
                                Rejeter
                            </a>
                    </div>
                    <div class="hide" style="display: none;">
                       <input type="text" class="search" name="exemp" style="width: 86%;margin:17px 0 17px;" placeholder="Scanner le code-barres de l'exemplaire"><br>
                       <a class="btnapp" style="width: 300px;padding:8px;" href="indexEm.php?codeEmprunt=<?=$contact['codeEmprunt']?>&confirm=yes">
                                Confirmation
                            </a>
                    </div>
                  </div>
                </article>
            <?php endforeach; ?>      
        </div>        
    </div>
    <script type="text/javascript">
  //convert json to JS array data.
  function arrayjsonbarcode(j) {
    json = JSON.parse(j);
    arr = [];
    for (var x in json) {
      arr.push(json[x]);
    }
    return arr;
  }


  jsonvalue = '<?php echo json_encode($array1) ?>';
  values = arrayjsonbarcode(jsonvalue);

  //generate barcodes using values data.
  for (var i = 0; i < values.length; i++) {
    JsBarcode("#barcode1" + values[i], values[i].toString(), {
      format: "CODE128B",
      lineColor: "#000",
      width: 1,
      height: 15,
      displayValue: true
      }
    );
  }
  
  jsonvalue1 = '<?php echo json_encode($array2) ?>';
  values1 = arrayjsonbarcode(jsonvalue1);

  //generate barcodes using values data.
  for (var i = 0; i < values1.length; i++) {
    JsBarcode("#barcode2" + values1[i], values1[i].toString(), {
      format: "CODE128B",
      lineColor: "#000",
      width: 1,
      height: 15,
      displayValue: true
      }
    );
  }
  
  jsonvalue2 = '<?php echo json_encode($array3) ?>';
  values2 = arrayjsonbarcode(jsonvalue2);

  //generate barcodes using values data.
  for (var i = 0; i < values2.length; i++) {
    JsBarcode("#barcode3" + values2[i], values2[i].toString(), {
      format: "CODE128B",
      lineColor: "#000",
      width: 1,
      height: 15,
      displayValue: true
      }
    );
  }



</script>
<?=template_footer()?>