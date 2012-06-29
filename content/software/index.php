<table id="results" summary="">
	<thead>
		<tr>
			<th>Nom</th><th>Nom de version</th><th>Numéro de version</th><th>Description</th><th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$softwares = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		foreach($softwares as $software){
	?>
		<tr>
			<td><?php echo $software['name'];?></td>
			<td><?php echo $software['versionname'];?></td>
			<td><?php echo $software['versionnumber'];?></td>
			<td><?php echo $software['description'];?></td>
			<td>
				<ul class="actions">
					<li>
						<a href="index.php?page=<?php echo $page_current;?>&amp;action=edit&amp;id=<?php echo $software['id'];?>">
							<img alt="" src="pic/pencil.png"/>Voir / Modifier
						</a>
					</li>
					<?php if($_SESSION["user"]["isadministrator"]){?>
					<li>
						<a class="warning" href="warning.php?page=<?php echo $page_current;?>&amp;id=<?php echo $software['id'];?>" rel="#warning-software">
							<img alt="" src="pic/minus.png"/>Supprimer
						</a>
					</li>
					<?php }?>
				</ul>
			</td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>
<div class="warning-popup" id="warning-software"></div>