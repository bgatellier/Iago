<ul>
	<li><a class="current" href="#search"><img alt="" src="pic/magnifier.png"/>Rechercher</a></li><?php if($_SESSION["user"]["isadministrator"]){?><li><a href="#add"><img alt="" src="pic/plus.png"/>Ajouter</a></li><?php }?>
</ul>
<form action="" id="search" method="post">
	<p>
		<label for="search-lastname">Nom</label>
		<input id="search-lastname" name="search-lastname" type="text"/>
	</p>
	<p>
		<label for="search-firstname">Prénom</label>
		<input id="search-firstname" name="search-firstname" type="text"/>
	</p>
	<p>
		<label for="search-login">Identifiant</label>
		<input id="search-login" name="search-login" type="text"/>
	</p>
	<p>
		<button name="search-submit" type="submit"><img alt="" src="pic/magnifier.png"/>Rechercher</button>
	</p>
</form>
<?php if($_SESSION["user"]["isadministrator"]){?>
<form action="" id="add" method="post">
	<p>
		<label for="add-lastname">Nom</label>
		<input id="add-lastname" maxlength="200" name="add-lastname" type="text" value="<?php echo $user_current["lastname"];?>"/>
	</p>
	<p>
		<label for="add-firstname">Prénom</label>
		<input id="add-firstname" maxlength="200" name="add-firstname" type="text" value="<?php echo $user_current["firstname"];?>"/>
	</p>
	<p>
		<label for="add-login">Identifiant</label>
		<input id="add-login" maxlength="50" name="add-login" type="text" value="<?php echo $user_current["login"];?>"/>
	</p>
	<p>
		<label for="add-password">Mot de passe</label>
		<input id="add-password" maxlength="30" name="add-password" type="password"/>
	</p>
	<p>
		<label for="add-password_confirm">Confirmation du mot de passe</label>
		<input id="add-password_confirm" maxlength="30" name="add-password_confirm" type="password"/>
	</p>
	<p>
		<label for="add-isadministrator">Administrateur (droits de modification et de suppression)</label>
		<input id="add-isadministrator" name="add-isadministrator" type="checkbox"/>
	</p>
	<p>
		<button id="add-submit" name="add-submit" type="submit"><img alt="" src="pic/disk-black.png"/>Ajouter</button>
	</p>
</form>
<?php }?>