<?php
	// Initialisation des valeurs des différents champs du formulaire
	$user_current = array("lastname"=>"","firstname"=>"","login"=>"","isadministrator"=>"");
	if( $id_current>0 ){
		$PDOStatement = $IagoDB->prepare("SELECT `lastname`,`firstname`,`login`,`isadministrator` FROM $page_current WHERE `id`='$id_current';");
		$PDOStatement->execute();
		$user_current = $PDOStatement->fetch(PDO::FETCH_ASSOC);
	}
?>
<h1>Utilisateur : <?php echo $user_current["lastname"];?> <?php echo $user_current["firstname"];?></h1>
<fieldset>
	<legend>Caractéristiques</legend>
	<form action="" method="post">
		<div id="details">
			<p>
				<label for="lastname">Nom</label>
				<input id="lastname" maxlength="200" name="lastname" type="text" value="<?php echo $user_current["lastname"];?>"/>
			</p>
			<p>
				<label for="firstname">Prénom</label>
				<input id="firstname" maxlength="200" name="firstname" type="text" value="<?php echo $user_current["firstname"];?>"/>
			</p>
		</div>
		<div id="logins">
			<p>
				<label for="login">Identifiant</label>
				<input id="login" maxlength="50" name="login" type="text" value="<?php echo $user_current["login"];?>"/>
			</p>
			<div>
				<p>
					<label for="password">Mot de passe</label>
					<input id="password" maxlength="30" name="password" type="password"/>
				</p>
				<p>
					<label for="password_confirm">Confirmation du mot de passe</label>
					<input id="password_confirm" maxlength="30" name="password_confirm" type="password"/>
				</p>
			</div>
		</div>
		<div id="options">
			<p>
				<label for="isadministrator">Administrateur (droits de modification et de suppression)</label>
				<input<?php if($user_current["isadministrator"])echo ' checked="checked"';?> id="isadministrator" name="isadministrator" type="checkbox"/>
			</p>
		</div>
		<?php if($_SESSION["user"]["isadministrator"]){?>
		<p>
			<button id="edit-submit" name="edit-submit" type="submit"><img alt="" src="pic/disk-black.png"/>Enregistrer</button>
		</p>
		<?php }?>
	</form>
</fieldset>
<div class="warning-popup" id="warning-user"></div>