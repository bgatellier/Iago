<?php
	// Initialisation des valeurs des différents champs du formulaire
	$park_current = array("id_park"=>$park_main['id'],"name"=>"");
	if( $id_current>0 ){
		$PDOStatement = $IagoDB->prepare("SELECT `id_parent`,`name` FROM $page_current WHERE `id`='$id_current';");
		$PDOStatement->execute();
		$park_current = $PDOStatement->fetch(PDO::FETCH_ASSOC);
	}
?>
<h1>Parc : <?php echo $park_current["name"];?></h1>
<fieldset id="caracteristics">
	<legend>Caractéristiques</legend>
	<form action="" method="post">
		<p>
			<label for="name">Nom</label>
			<input id="name" maxlength="200" name="name" type="text" value="<?php echo $park_current["name"];?>"/>
		</p>
		<p>
			<label for="id_parent">Parc d'appartenance</label>
			<select id="id_parent" name="id_parent">
				<option value="0">Aucun</option>
				<?php
					$PDOStatement = $IagoDB->prepare("SELECT `id`,`name` FROM park ORDER BY `name` ASC;");
					$PDOStatement->execute();
					$parks = $PDOStatement->fetchAll();
					foreach($parks as $park){
						$selected = "";
						if( $park['id']==$park_current["id_parent"]){
							$selected = ' selected="selected"';
						}
						echo '<option'.$selected.' value="'.$park['id'].'">'.$park['name'].'</option>';
					}
				?>
			</select>
		</p>
		<?php if($_SESSION["user"]["isadministrator"]){?>
		<p>
			<button id="edit-submit" name="edit-submit" type="submit"><img alt="" src="pic/disk-black.png"/>Enregistrer</button>
		</p>
		<?php }?>
	</form>
</fieldset>
<fieldset>
	<legend>Logiciels installés</legend>
	<?php
		$PDOStatement = $IagoDB->prepare("SELECT sp.id,s.name,s.versionname,s.versionnumber,s.description FROM software_park sp LEFT JOIN software s ON s.id=sp.id_software WHERE sp.id_park='$id_current' ORDER BY s.name ASC, s.versionname ASC");
		$PDOStatement->execute();
		$softwares = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		
		if( empty($softwares) ){
	?><p>Aucun logiciel n'est installé dans ce parc</p><?php
		}
		else{
	?>
	<table id="results" summary="">
		<thead>
			<tr>
				<th>Nom</th><th>Nom de version</th><th>Numéro de version</th><th>Description</th><?php if($_SESSION["user"]["isadministrator"]){?><th>&nbsp;</th><?php }?>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($softwares as $software_properties){
			?>
			<tr>
				<td><?php echo $software_properties['name'];?></td>
				<td><?php echo $software_properties['versionname'];?></td>
				<td><?php echo $software_properties['versionnumber'];?></td>
				<td><?php echo $software_properties['description'];?></td>
				<?php if($_SESSION["user"]["isadministrator"]){?>
				<td>
					<ul class="actions">
						<li>
							<a class="warning" href="warning.php?page=<?php echo $page_current;?>&amp;action=edit&amp;id=<?php echo $id_current;?>&amp;subid=<?php echo $software_properties['id'];?>" rel="#warning-park">
								<img alt="" src="pic/minus.png"/>Supprimer du parc
							</a>
						</li>
					</ul>
				</td>
				<?php }?>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
	<?php
		}
		
		// Sélection de l'ensemble des logiciels n'étant pas installés dans le parc 
		$PDOStatement = $IagoDB->prepare("SELECT id,name,versionname FROM software WHERE id NOT IN (SELECT id_software FROM software_park WHERE id_park='$id_current') ORDER BY name ASC,versionname ASC");
		$PDOStatement->execute();
		$softwares = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		
		if( $_SESSION["user"]["isadministrator"]&&!empty($softwares) ){
	?>
	<form action="" method="post">
		<p>
			<label for="id_software">Logiciel à ajouter au parc</label>
			<select id="id_software" name="id_software">
			<?php
				foreach($softwares as $software_properties){
					echo '<option value="'.$software_properties["id"].'">'.$software_properties["name"]." ".$software_properties["versionname"].'</option>';
				}
			?>
			</select>
		</p>
		<p>
			<button name="submit_software" type="submit"><img alt="" src="pic/plus.png"/>Ajouter</button>
		</p>
	</form>
	<?php
		}
	?>
</fieldset>
<div class="warning-popup" id="warning-park"></div>