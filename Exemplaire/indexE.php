<?php

include '../functions.php';
$pdo = pdo_connect_mysql();
$search2 = $_GET['search2']??'';
if($search2){
    $statement2 = $pdo->prepare
    (' select e.codeBar,e.ISBN,l.titreLiv,ed.anneeEdi,e.etatEx
    from exemplaire e,livre l,edition ed
    where e.ISBN=l.ISBN
    and e.codeEdi = ed.codeEdi 
    and e.codeBar like :title');
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
            <form class="form-inline" method="post" action="../PDF/FICHIERS/PDFEX.php">
              <button type="submit" id="pdf" name="generate_pdf" style="text-align: center;font-size:large" >
              <i style="color: rgb(250, 49, 49);" class="fas fa-file-pdf"></i>
               exporter PDF
              </button>
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
                    <a href="updateE.php?codeBar=<?=$contact2['codeBar']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="deleteE.php?codeBar=<?=$contact2['codeBar']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
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

  jsonvalue1 = '<?php echo json_encode($array1) ?>';
  values1 = arrayjsonbarcode(jsonvalue1);

  //generate barcodes using values data.
  for (var i = 0; i < values1.length; i++) {
    JsBarcode("#barcode1" + values1[i], values1[i].toString(), {
      format: "CODE128B",
      lineColor: "#000",
      width: 1,
      height: 15,
      displayValue: true
      }
    );
  }
  
  jsonvalue2 = '<?php echo json_encode($array2) ?>';
  values2 = arrayjsonbarcode(jsonvalue2);

  //generate barcodes using values data.
  for (var i = 0; i < values2.length; i++) {
    JsBarcode("#isbn" + values2[i], values2[i].toString(), {
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