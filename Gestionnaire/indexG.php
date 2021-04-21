<?php
include '../functions.php';
$pdo = pdo_connect_mysql();
$search = $_GET['search']??'';


if($search){
    $statement = $pdo->prepare('SELECT * FROM Gestionnaire WHERE CBGest LIKE :title  ORDER BY dateinscripG DESC ');
    $statement->bindValue(':title',"%$search%");
    $contacts = $statement->fetchAll(PDO::FETCH_ASSOC);
    
}else{
    $statement = $pdo->prepare('SELECT * FROM Gestionnaire ORDER BY CBGEst');
    $contacts = $statement->fetchAll(PDO::FETCH_ASSOC);
}
    $statement->execute();
    $contacts = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<?=template_header('Read')?>

    <div class="container">   
            <div class="head">
              <h2>Gestionnaires</h2><br>
            <a class="create" href="createG.php">Ajouter un gestionnaire</a>
            </div>
            <form>
            <div class="cont" style="margin-top: 15px;margin-bottom:15px;">
              <input type="text" class="search" name="search"
               placeholder="Scanner le code barre"
               value="<?php echo $search; ?>">
              <button type="submit" class="btnsearch">
                <i class="fas fa-search">
                </i>
              </button>
            </div>
            </form>
            <div class="form-inline" style="display: flex;flex-flow:column nowrap;">
            <form class="form-inline" method="post" action="../PDF/FICHIERS/PDFG.php">
              <button type="submit" id="pdf" name="generate_pdf" style="text-align: center;font-size:large" >
              <i style="color: rgb(250, 49, 49);" class="fas fa-file-pdf"></i>
               exporter PDF
              </button>
            </form>
            
            </div>
            <div style="overflow-x: auto;overflow-y:auto;">
    <table>
        <thead>
            <tr>              
                <td>Code-barres</td>
                <td>Cin</td>
                <td>Nom</td>
                <td>Prénom</td>
                <td>Email</td>
                <td>Telephone</td>
                <td>Mot de passe</td>
                <td>Date d'inscription</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact):
                
                $array[] = $contact['CBGest'];
                
                ?>
            <tr>
                <td>
                <svg id='<?php echo "barcode".$contact['CBGest']; ?>'>
                </td>
                <td><?=$contact['cinG']?></td>
                <td><?=$contact['nomG']?></td>
                <td><?=$contact['prenomG']?></td>
                <td><?=$contact['emailG']?></td>
                <td><?=$contact['telephoneG']?></td>
                <td><?=$contact['mdpG']?></td> 
                <td><?=$contact['dateinscripG']?></td>
                <td class="actions">
                    <a href="updateG.php?CBGest=<?=$contact['CBGest']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="deleteG.php?CBGest=<?=$contact['CBGest']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
              <td colspan="9">
              <?php
                global $val1;
                $stmt1 = $pdo->prepare('select count(*) as nombre from gestionnaire');
                $stmt1 -> execute();
                $emprunts1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                foreach($emprunts1 as $emprunts){
                  $val1 = $emprunts['nombre'];
                }
                ?>
                Le nombre total des gestionnaires est <?php echo $val1; ?>
              </td>
            </tr>
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
      format: "codabar",
      lineColor: "#000",
      width: 1,
      height: 15,
      displayValue: true
      }
    );
  }
</script>


<?=template_footer()?>