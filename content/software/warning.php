<?php
	




	$PDOstatement = $IagoDB->prepare("SELECT name, versionname FROM software WHERE id='$id_to_delete'");
	$PDOstatement->execute();
	$software = $PDOstatement->fetch(PDO::FETCH_ASSOC);
?>
<h2>Suppression du logiciel <em><?php echo $software['name'];?> <?php echo $software['versionname'];?></em></h2>
<p>La suppression du logiciel s'effectuera pour l'ensemble des parcs dans lesquels il est installé.</p>