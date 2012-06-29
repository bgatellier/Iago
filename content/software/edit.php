<?php
	// Initialisation des valeurs des différents champs du formulaire
	$software_current = array("name"=>"","versionname"=>"","versionnumber"=>"","description"=>"");
	if( $id_current>0 ){
		$PDOStatement = $IagoDB->prepare("SELECT `name`,`versionname`,`versionnumber`,`description` FROM $page_current WHERE `id`='$id_current';");
		$PDOStatement->execute();
		$software_current = $PDOStatement->fetch(PDO::FETCH_ASSOC);
	}
?>
<h1>Logiciel : <?php echo $software_current["name"];?> <?php echo $software_current["versionname"];?></h1>
<fieldset>
	<legend>Caractéristiques</legend>
	<form action="" method="post">	
		<p>
			<label for="name">Nom</label>
			<input id="name" maxlength="200" name="name" type="text" value="<?php echo $software_current["name"];?>"/>
		</p>
		<p>
			<label for="versionname">Nom de version</label>
			<input id="versionname" maxlength="200" name="versionname" type="text" value="<?php echo $software_current["versionname"];?>"/>
		</p>
		<p>
			<label for="versionnumber">Numéro de version</label>
			<input id="versionnumber" maxlength="50" name="versionnumber" type="text" value="<?php echo $software_current["versionnumber"];?>"/>
		</p>
		<p>
			<label for="description">Description</label>
			<textarea cols="50" id="description" name="description" rows="6"><?php echo $software_current["description"];?></textarea>
		</p>
		<?php if($_SESSION["user"]["isadministrator"]){?>
		<p>
			<button id="edit-submit" name="edit-submit" type="submit"><img alt="" src="pic/disk-black.png"/>Enregistrer</button>
		</p>
		<?php }?>
	</form>
</fieldset>
<fieldset>
	<legend>Présence dans les parcs</legend>
	<?php
		$PDOStatement = $IagoDB->prepare("SELECT sp.id,p.name FROM software_park AS sp LEFT JOIN park AS p ON p.id=sp.id_park WHERE sp.id_software='$id_current' ORDER BY p.name ASC");
		$PDOStatement->execute();
		$parks = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		
		if( empty($parks) ){
	?><p>Ce logiciel n'est installé dans aucun parc.</p><?php
		}
		else{
	?>
	<table id="results" summary="">
		<thead>
			<tr>
				<th>Nom</th><?php if($_SESSION["user"]["isadministrator"]){?><th>&nbsp;</th><?php }?>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($parks as $park_properties){
			?>
			<tr>
				<td><?php echo $park_properties['name'];?></td>
				<?php if($_SESSION["user"]["isadministrator"]){?>
				<td>
					<ul class="actions">
						<li>
							<a class="warning" href="warning.php?page=<?php echo $page_current;?>&amp;action=edit&amp;id=<?php echo $id_current;?>&amp;subid=<?php echo $park_properties['id'];?>" rel="#warning-software">
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
		
		// Sélection de l'ensemble des parcs dans lesquels le logiciel n'est pas installé 
		$PDOStatement = $IagoDB->prepare("SELECT id,name FROM park WHERE id_parent!='0' AND id NOT IN (SELECT id_park FROM software_park WHERE id_software='$id_current') ORDER BY name ASC");
		$PDOStatement->execute();
		$parks = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		
		if( $_SESSION["user"]["isadministrator"]&&!empty($parks) ){
	?>
	<form action="" method="post">
		<p>
			<label for="id_park">Installer dans un autre parc</label>
			<select id="id_park" name="id_park">
			<?php
				foreach($parks as $park_properties){
					echo '<option value="'.$park_properties["id"].'">'.$park_properties["name"].'</option>';
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
<div class="warning-popup" id="warning-software"></div>