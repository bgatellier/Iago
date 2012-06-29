<table id="results" summary="">
	<thead>
		<tr>
			<th>Nom</th><th>Prénom</th><th>Identifiant</th><th>Administrateur</th><th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$users = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		foreach($users as $user){
	?>
		<tr>
			<td><?php echo $user['lastname'];?></td>
			<td><?php echo $user['firstname'];?></td>
			<td><?php echo $user['login'];?></td>
			<td><?php echo $user['isadministrator'];?></td>
			<td>
				<ul class="actions">
					<li>
						<a href="index.php?page=<?php echo $page_current;?>&amp;action=edit&amp;id=<?php echo $user['id'];?>">
							<img alt="" src="pic/pencil.png"/>Voir / Modifier
						</a>
					</li>
					<?php if($_SESSION["user"]["isadministrator"]){?>
					<li>
						<a class="warning" href="warning.php?page=<?php echo $page_current;?>&amp;id=<?php echo $user['id'];?>" rel="#warning-user">
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
	<div class="warning-popup" id="warning-user"></div>