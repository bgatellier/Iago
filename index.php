<?php
	session_start();
	include_once("connect.php");
	
	if( isset($_GET["disconnect"]) ){
		unset($_SESSION["user"]);
	}
	
	if( isset($_POST["connexion"]) ){
		$PDOStatement = $IagoDB->prepare("SELECT * FROM `users` WHERE `login`='{$_POST["login"]}' AND `password`='".md5($_POST["password"])."';");
		$PDOStatement->execute();
		$results = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		
		if( count($results)==1 ){
			$_POST = array();
			
			foreach($results[0] as $field_name=>$field_value){
				$_SESSION["user"][$field_name] = $field_value;
			}
		}
	}


	if( !isset($_SESSION["user"]) ){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>Iago - Connexion</title>
	
	<link href="css/login.css" media="screen" rel="stylesheet" type="text/css"/>
</head>
<body onload="document.getElementById('login').focus();">
	<form action="index.php" method="post">
		<p>
			<label for="login">Identifiant</label>
			<input id="login" maxlength="50" name="login" type="text"/>
		</p>
		<p>
			<label for="password">Mot de passe</label>
			<input id="password" name="password" type="password"/>
		</p>
		<p><input name="connexion" type="submit" value="Se connecter"/></p>
	</form>
</body>
</html>
<?php
	}
	else{
		// Parc sélectionné par défaut
		$PDOStatement = $IagoDB->prepare("SELECT `id`,`name` FROM park WHERE `id_parent`='0' AND `default`=1;");
		$PDOStatement->execute();
		$park_main = $PDOStatement->fetch(PDO::FETCH_ASSOC);
		
		// Page sélectionnée
		$pages_allowed = array("park"=>"Parcs","software"=>"Logiciels","users"=>"Utilisateurs");
		$page_current = array_shift( array_keys($pages_allowed) );
		if( isset($_GET['page'])&&in_array($_GET['page'],array_keys($pages_allowed)) ){
			$page_current = $_GET['page'];
		}
		
		// Action à effectuer
		$actions_allowed = array("index","edit");
		$action_current = array_shift( array_values($actions_allowed) );
		if( isset($_GET['action'])&&in_array($_GET['action'],array_values($actions_allowed)) ){
			$action_current = $_GET['action'];
		}
		
		// ID de l'entité à modifier
		$id_current = 0;
		if( $action_current=="edit"&&isset($_GET['id']) ){	// Mode ajout/modification && ID existe
			$id_current = (int)$_GET['id'];
			
			if( $id_current>0 ){
				// Vérification de l'existance de l'identifiant en base
				$PDOStatement = $IagoDB->prepare("SELECT `id` FROM `$page_current` WHERE id='$id_current' LIMIT 0,1;");
				$PDOStatement->execute();
				$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
				
				if( empty($results) ){	// L'ID n'existe pas
					$id_current = 0;
				}
			}
		}
		
		// Affichage et de traitement des formulaires
		$action = "search";
		if( isset($_POST) ){	// Gestion de la soumission des formulaires
			$submit_field = preg_grep("`^[a-zA-Z]+-submit$`", array_keys($_POST));	// Interception du champ de formulaire dont le nom se termine par : submit
			if( count($submit_field)==1 ){
				$submit_field_name = array_shift($submit_field);
				$action = substr($submit_field_name,0,strpos($submit_field_name,"submit")-1);	// Suppression des caractères précédant le mot-clé : submit
				unset($_POST[$submit_field_name]);
			}
		}
		include_once("content/$page_current/process.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>Iago - <?php echo $park_main['name'];?></title>
	
	<link href="css/main.css" media="screen" rel="stylesheet" type="text/css"/>
	<?php if(file_exists("content/$page_current/css/specific.css")){?><link href="<?php echo "content/$page_current/css/specific.css";?>" media="screen" rel="stylesheet" type="text/css"/><?php }?>
	<?php if($action=="search"||$action=="edit"){?><link href="css/print.css" media="print" rel="stylesheet" type="text/css"/><?php }?>
	
	<script src="js/jquery.tools.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var triggers = $(".warning").overlay({
				mask:{
					color: '#000',
					loadSpeed: 400,
					opacity: 0.6
				},
				closeOnClick: false,
				onBeforeLoad: function(){
					// grab wrapper element inside content
					var wrap = this.getOverlay();

					// load the page specified in the trigger
					wrap.load(this.getTrigger().attr("href"));
				}
			});

			// setup ul.tabs to work as tabs for each div directly under div.panes
			$("#sidebar ul").tabs("#sidebar form");
		});
	</script>
</head>
<body>
	<ul id="navigation">
	<?php
		foreach($pages_allowed as $page_name => $page_label){
			$page_selected = "";
			if( $page_name==$page_current ){
				$page_selected = ' class="current"';
			}
			echo '<li><a'.$page_selected.' href="index.php?page='.$page_name.'">'.$page_label.'</a></li>';
		}
	?>
		<li id="disconnect">
			<a href="index.php?disconnect">
				<img alt="" src="pic/door-open-out.png"/>Se déconnecter
			</a>
		</li>
	</ul>
	<div id="sidebar"><?php include_once("content/".$page_current."/sidebar.php");?></div>
	<div class="content" id="<?php echo $page_current;?>"><?php include_once("content/".$page_current."/".$action_current.".php");?></div>
</body>
</html>
<?php
	}
?>