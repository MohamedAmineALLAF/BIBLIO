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