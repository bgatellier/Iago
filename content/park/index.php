<table id="results" summary="">
	<thead>
		<tr>
			<th>Nom</th><th>Parc d'appartenance</th><th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$parks = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		foreach($parks as $park){
	?>
		<tr>
			<td><?php echo $park['parkname'];?></td>
			<td><?php echo $park['parkname_parent'];?></td>
			<td>
				<ul class="actions">
					<li>
						<a href="index.php?page=<?php echo $page_current;?>&amp;action=edit&amp;id=<?php echo $park['id'];?>">
							<img alt="" src="pic/pencil.png"/>Voir / Modifier
						</a>
					</li>
					<?php if($_SESSION["user"]["isadministrator"]){?>
					<li>
						<a class="warning" href="warning.php?page=<?php echo $page_current;?>&amp;id=<?php echo $park['id'];?>" rel="#warning-park">
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
<div class="warning-popup" id="warning-park"></div>