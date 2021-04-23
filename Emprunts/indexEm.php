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
        ('select  e.codeEmprunt,e.dateDebut,e.dateFin,l.titreLiv,l.ISBN,et.CNI,et.nomEtu,et.prenomEtu,d.libelleDis
        from emprunt e,livre l,exemplaire ex,etudiant et,discipline d
        where l.ISBN=ex.ISBN
        and d.codeDis=l.codeDis
        and ex.codeBar = e.codeBar 
        and	e.CBR=et.CBR
        and e.etat = 0
        order by e.dateDebut desc
        LIMIT 1;');
    $statement2->execute();
    $contacts2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

    
    
    if (isset($_POST['codeEm'])) {
      if (isset($_POST['confirme'])) {
          if ($_POST['confirme'] == 'yes') {
            if(isset($_POST['exeme'])){
              $stmt = $pdo->prepare('
              update emprunt,exemplaire 
              set emprunt.Etat = 1
              ,exemplaire.etatEx = 0
              ,emprunt.codeBar = ?
              ,emprunt.CBGest = ?
              WHERE emprunt.codeBar = exemplaire.codeBar
              and emprunt.codeEmprunt = ?');
              $stmt->execute([$_POST['exeme'],$value,$_POST['codeEm']]);      
              }
            }else{
              $stmt = $pdo->prepare('delete from emprunt
              WHERE codeEmprunt = ?');
              $stmt->execute([$_POST['codeEm']]);
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
                        <a class="btnapp shw"   style="cursor: pointer;">
                            <i class="fas fa-check"></i>
                                Confirmer
                            </a>
                        <a id="del" class="btndelete" style="cursor: pointer;">
                            <i class="fas fa-times"></i>
                                Rejeter
                            </a>
                          
                    </div>
                    <div>
                    
                    </div>
                    <div id="" class="hide" style="display: none;">
                    
                          <input id="exe" type="text" class="searching" name="exemp" style="width: 100%;margin:17px 0 17px;" placeholder="Scanner le code-barres de l'exemplaire"><br>
                          <button class="btna" style="width: 100%;padding:8px;font-size:medium;cursor:pointer">
                            Validation
                          </button>
                          </div> 

                          <script >
                            $(document).ready(function(){
                            $('.btna').click(function() {
                                var exem = $('#exe').val();
                                var confirm = 'yes';
                              $.ajax({
                                url: 'indexEm.php',
                                type: 'POST',
                                data: { exeme : exem,
                                  codeEm : <?=$contact['codeEmprunt']?>,
                                  confirme : confirm
                                },

                                success: function(output){
                                  window.location.reload(true);
                                }
                              });
                            });
                          
                          
                            $('#del').click(function() {
                                var confirm = 'no';
                              $.ajax({
                                url: 'indexEm.php',
                                type: 'POST',
                                data: { 
                                  codeEm : <?=$contact['codeEmprunt']?>,
                                  confirme : confirm
                                },
                                success: function(output){
                                  window.location.reload(true);
                                }
                              });
                            });
                            
                          });
                            </script>
                             
                  </div>
                </article>
            <?php endforeach; ?>      
        </div>
        <h2 style="margin-top: 50px;">Emprunts retournés </h2>
        <div class="wrap">
              <?php
              if(isset($_POST['codeB'])){
              $stmt = $pdo -> prepare('UPDATE exemplaire,emprunt
              SET exemplaire.etatEx=1,emprunt.dateFin=CURRENT_DATE()
                  WHERE exemplaire.codeBar=emprunt.codeBar
                  AND exemplaire.codeBar=?
              ');
              $stmt -> execute([$_POST['codeB']]);
            }
              ?>

              <form action="indexEm.php" method="post">
                <input type="text" name="codeB" class="searching" style="width: 100%;" placeholder="Scanner le code-barres de l'exemplaire retourné">
                  <button type="submit" class="btna" style="width: 100%;padding:8px;font-size:medium;cursor:pointer;margin:5px 0 0 0">
                          Validation
                  </button>
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