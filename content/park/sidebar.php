<ul>
	<li><a class="current" href="#search"><img alt="" src="pic/magnifier.png"/>Rechercher</a></li><?php if($_SESSION["user"]["isadministrator"]){?><li><a href="#add"><img alt="" src="pic/plus.png"/>Ajouter</a></li><?php }?>
</ul>
<form action="index.php?page=<?php echo $page_current;?>" id="search" method="post">
	<p>
		<label for="search-name">Nom</label>
		<input id="search-name" name="search-name" type="text"/>
	</p>
	<p>
		<button name="search-submit" type="submit"><img alt="" src="pic/magnifier.png"/>Rechercher</button>
	</p>
</form>
<?php if($_SESSION["user"]["isadministrator"]){?>
<form action="index.php?page=<?php echo $page_current;?>" id="add" method="post">
	<p>
		<label for="add-name">Nom</label>
		<input id="add-name" maxlength="200" name="add-name" type="text" value=""/>
	</p>
	<p>
		<label for="add-id_parent">Parc d'appartenance</label>
		<select id="add-id_parent" name="add-id_parent">
			<option value="0">Aucun</option>
			<?php
				$PDOStatement_search = $IagoDB->prepare("SELECT `id`,`name` FROM park ORDER BY `name` ASC;");
				$PDOStatement_search->execute();
				$parks = $PDOStatement_search->fetchAll();
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
	<p>
		<button id="add-submit" name="add-submit" type="submit"><img alt="" src="pic/disk-black.png"/>Ajouter</button>
	</p>
</form>
<?php }?>