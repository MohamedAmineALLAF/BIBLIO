<?php include '../functions.php';
$pdo = pdo_connect_mysql();
global $bal;
if(isset($_POST['user'])) {
    
    echo "<h1>".$_POST['user']."</h1>";

 }

?>

<?=template_header('Read')?>

</head>
<body>
<input type="text" name="" id="message-content">
    <button id="save">
            men
    </button>
    <h1> <?php echo $bal ?> </h1>
</body>
<script>
    
</script>
</html> 

