<?php
include '../functions.php';
$pdo = pdo_connect_mysql();
$search = $_GET['search']??'';
if($search){
    $statement = $pdo->prepare
    ('SELECT l.ISBN,l.titreLiv,a.nomAut,a.prenomAut,d.libelleDis,l.nbExemplaire 
    FROM auteur a, rediger r, livre l,discipline d  
      WHERE a.codeAut=r.codeAut
      and r.ISBN=l.ISBN
      and d.codeDis=l.codeDis
      and l.ISBN like :title ');
    $statement->bindValue(':title',"%$search%");
}else{
  $statement = $pdo->prepare
  ('SELECT l.ISBN,l.titreLiv,a.nomAut,a.prenomAut,d.libelleDis,l.nbExemplaire 
  FROM auteur a, rediger r, livre l,discipline d  
    WHERE a.codeAut=r.codeAut
    and r.ISBN=l.ISBN
    and d.codeDis=l.codeDis
    order by l.ISBN');
}
    $statement->execute();
    $contacts = $statement->fetchAll(PDO::FETCH_ASSOC);

    $search2 = $_GET['search2']??'';
    if($search2){
        $statement2 = $pdo->prepare
        (' select e.codeBar,e.ISBN,l.titreLiv,ed.anneeEdi,e.etatEx
        from exemplaire e,livre l,edition ed
        where e.ISBN=l.ISBN
        and e.codeEdi = ed.codeEdi 
        and e.codeEdi like :title');
        $statement2->bindValue(':title',"%$search2%");
    }else{
      $statement2 = $pdo->prepare
      ('select e.codeBar,e.ISBN,l.titreLiv,ed.anneeEdi,e.etatEx
      from exemplaire e,livre l,edition ed
      where e.ISBN=l.ISBN
      and e.codeEdi = ed.codeEdi
        order by e.codeBar');
    }
        $statement2->execute();
        $contacts2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header('Read')?>

    <div class="container">   
            <div class="head">
              <h2>Livres</h2><br>
              
              <a class="create" href="createL.php">Ajouter un livre</a>
            </div>
            <form>
            <div class="cont" style="margin-top: 20px;margin-bottom:15px;">

              <input type="text" class="search" name="search"
               placeholder="Scanner ISBN du livre"
               value="<?php echo $search; ?>">
              <button type="submit" class="btnsearch">
                <i class="fas fa-search">
                </i>
              </button>

            </div>
            </form>
            <div style="overflow-x: auto;overflow-y:auto;">
    <table>
        <thead>
            <tr>              
                <td>ISBN</td>
                <td>Titre</td>
                <td>Nom Auteur</td>
                <td>Prénom Auteur</td>
                <td>Discipline</td>
                <td>Nb-exemplaires</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact):
                
                $array[] = $contact['ISBN'];
                
                ?>
            <tr>
                <td>
                <svg id='<?php echo "barcode".$contact['ISBN']; ?>'>
                </td>
                <td><?=$contact['titreLiv']?></td>
                <td><?=$contact['nomAut']?></td>
                <td><?=$contact['prenomAut']?></td>
                <td><?=$contact['libelleDis']?></td>
                <td><?=$contact['nbExemplaire']?></td>
                <td class="actions">
                    <a href="updateL.php?ISBN=<?=$contact['ISBN']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="deleteL.php?ISBN=<?=$contact['ISBN']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div> 
    <div class="container">   
            <div class="head">
              <h2>Exemplaires</h2><br> 
              <a class="create" href="createE.php">Ajouter un exemplaire</a>
            </div>
            <form>
            <div class="cont" style="margin-top: 20px;margin-bottom:15px;">

              <input type="text" class="search" name="search2"
               placeholder="Scanner ISBN du livre ou code-barres du livre"
               value="<?php echo $search2; ?>">
              <button type="submit" class="btnsearch">
                <i class="fas fa-search">
                </i>
              </button>

            </div>
            </form>
            <div style="overflow-x: auto;overflow-y:auto;">
    <table>
        <thead>
            <tr>              
                <td>Code-barres</td>
                <td>ISBN</td>
                <td>Titre livre</td>
                <td>Année édition</td>
                <td>Etat exemplaire</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts2 as $contact2):
                
                $array1[] = $contact2['codeBar'];
                $array2[] = $contact2['ISBN'];
                ?>
            <tr>
                <td>
                  <svg id='<?php echo "barcode1".$contact2['codeBar']; ?>'>
                </td>
                <td><svg id='<?php echo "isbn".$contact2['ISBN']; ?>'></td>
                <td><?=$contact2['titreLiv']?></td>
                <td><?=$contact2['anneeEdi']?></td>
                <td><?=$contact2['etatEx']?></td>
                <td class="actions">
                    <a href="updateL.php?ISBN=<?=$contact2['ISBN']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="deleteL.php?ISBN=<?=$contact2['ISBN']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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

  //convert PHP array to json data.
  jsonvalue = '<?php echo json_encode($array) ?>';
  values = arrayjsonbarcode(jsonvalue);

  
  

  //generate barcodes using values data.
  for (var i = 0; i < values.length; i++) {
    JsBarcode("#barcode" + values[i], values[i].toString(), {
      format: "EAN13",
      lineColor: "#000",
      width: 1,
      height: 15,
      displayValue: true
      }
    );
  }

  

</script>
<?=template_footer()?>