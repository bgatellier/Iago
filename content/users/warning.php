<?php
	$PDOstatement = $IagoDB->prepare("SELECT lastname, firstname FROM users WHERE id='$id_to_delete'");
	$PDOstatement->execute();
	$user = $PDOstatement->fetch(PDO::FETCH_ASSOC);
?>
<h2>Suppression de l'utilisateur <em><?php echo $user['lastname'];?> <?php echo $user['firstname'];?></em></h2>