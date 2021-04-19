<?php

include '../functions.php';
$pdo = pdo_connect_mysql();
$search2 = $_GET['search2']??'';
if($search2){
    $statement2 = $pdo->prepare
    ('select l.titreLiv,l.ISBN,et.CNI,et.CBR
    from emprunt e,livre l,exemplaire ex,etudiant et
    where l.ISBN=ex.ISBN
    and ex.codeBar = e.codeBar 
    and	e.CBR=et.CBR
    and l.ISBN= :title ;');
    $statement2->bindValue(':title',"%$search2%");
}else{
  $statement2 = $pdo->prepare
        ('select l.titreLiv,l.ISBN,et.CNI,et.CBR
        from emprunt e,livre l,exemplaire ex,etudiant et
        where l.ISBN=ex.ISBN
        and ex.codeBar = e.codeBar 
        and	e.CBR=et.CBR;');
}
    $statement2->execute();
    $contacts2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Read')?>

<div class="container">   
            <div class="head">
              <h2>Emprunts</h2><br> 
            </div>
            <form>
              <div class="cont" style="margin-top: 20px;margin-bottom:15px;">
              <input type="text" class="search" name="search2"
               placeholder="Scanner ISBN du livre, code-barres "
               value="<?php echo $search2; ?>">
                    <button type="submit" class="btnsearch">
                        <i class="fas fa-search">
                        </i>
                     </button>
                    </div>
            </form>
            <div class="grid">
            <?php foreach ($contacts2 as $contact):
                $array1[] = $contact['CBR'];
                $array2[] = $contact['ISBN'];
                ?>
                <article>
                    <div class="text">
                    <h3 >Etudiant : 
                    <svg id='<?php echo "barcode1".$contact['CBR']; ?>'>
                        <?= $contact['CBR'] ?>
                    </h3>
                    <h3>
                        CIN : 
                        <?= $contact['CNI'] ?>
                    </h3>
                    <h3>
                        Titre livre  :  
                        <?= $contact['titreLiv'] ?>
                    </h3>
                    <h3 >
                        ISBN :
                        <svg id='<?php echo "barcode2".$contact['ISBN']; ?>'>
                        <?= $contact['ISBN'] ?> </h3>
                    <div class="btn">
                        <a class="btnapp" href="">
                            <i class="fas fa-check"></i>
                                Confirmer
                            </a>
                        <a class="btndelete" href="">
                            <i class="fas fa-times"></i>
                                Rejeter
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