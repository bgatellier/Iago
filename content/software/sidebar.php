<ul>
	<li><a class="current" href="#search"><img alt="" src="pic/magnifier.png"/>Rechercher</a></li><?php if($_SESSION["user"]["isadministrator"]){?><li><a href="#add"><img alt="" src="pic/plus.png"/>Ajouter</a></li><?php }?>
</ul>
<form action="index.php?page=<?php echo $page_current;?>" id="search" method="post">
	<p>
		<label for="search-name">Nom</label>
		<input id="search-name" name="search-name" type="text"/>
	</p>
	<p>
		<label for="search-versionname">Nom de version</label>
		<input id="search-versionname" name="search-versionname" type="text"/>
	</p>
	<p>
		<label for="search-versionnumber">Numéro de version</label>
		<input id="search-versionnumber" name="search-versionnumber" type="text"/>
	</p>
	<p>
		<label for="search-description">Description</label>
		<input id="search-description" name="search-description" type="text"/>
	</p>
	<p>
		<button name="search-submit" type="submit"><img alt="" src="pic/magnifier.png"/>Rechercher</button>
	</p>
</form>
<?php if($_SESSION["user"]["isadministrator"]){?>
<form action="index.php?page=<?php echo $page_current;?>" method="post">	
	<p>
		<label for="add-name">Nom</label>
		<input id="add-name" maxlength="200" name="add-name" type="text" value=""/>
	</p>
	<p>
		<label for="add-versionname">Nom de version</label>
		<input id="add-versionname" maxlength="200" name="add-versionname" type="text" value=""/>
	</p>
	<p>
		<label for="add-versionnumber">Numéro de version</label>
		<input id="add-versionnumber" maxlength="50" name="add-versionnumber" type="text" value=""/>
	</p>
	<p>
		<label for="add-description">Description</label>
		<textarea cols="50" id="add-description" name="add-description" rows="6"></textarea>
	</p>
	<p>
		<button id="add-submit" name="add-submit" type="submit"><img alt="" src="pic/disk-black.png"/>Ajouter</button>
	</p>
</form>
<?php }?>