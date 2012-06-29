<?php
	$PDOstatement = $IagoDB->prepare("SELECT name FROM park WHERE id='$id_to_delete'");
	$PDOstatement->execute();
	$park = $PDOstatement->fetch(PDO::FETCH_ASSOC);
?>
<h2>Suppression du parc <em><?php echo $park['name'];?></em></h2>
<p>Les parcs qu'il contient feront ensuite parti du parc auquel il appartient.</p>