<?php
include '../functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['codeBar'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM exemplaire WHERE codeBar = ?');
    $stmt->execute([$_GET['codeBar']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('exemplaire n exites pas');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM exemplaire WHERE codeBar = ?');
            $stmt->execute([$_GET['codeBar']]);
            $msg = 'Vous avez supprimé l exemplaire !';
        } else {
            header('Location: indexE.php');
            exit;
        }
    }
} else {
    exit('No codeBar specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete" style="text-align: center;">
	<h2>Supprimer l'exemplaire #<?=$contact['codeBar']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p style="color: black;font-size: 1.2rem;">Voulez vous vraiment supprimer<br> 
    l'exemplaire avec le code-barres <br> <strong> <?=$contact['codeBar']?> </strong> ?</p>
    <div class="yesno">
        <a href="deleteG.php?codeBar=<?=$contact['codeBar']?>&confirm=yes">Oui</a>
        <a href="deleteG.php?codeBar=<?=$contact['codeBar']?>&confirm=no">Non</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>