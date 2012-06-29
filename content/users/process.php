<?php
	$sql = "";
	
	switch($action){
		// Suppression de l'utilisateur
		case "delete":	
			if($_SESSION["user"]["isadministrator"]){
				$PDOStatement = $IagoDB->prepare("DELETE FROM $page_current WHERE id='{$_POST["delete"]}'");
				$PDOStatement->execute();
				
				$sql.= "SELECT id,lastname,firstname,login,isadministrator FROM $page_current";
			}
		break;
		
		// Modification de l'utilisateur
		case "edit":
			if($_SESSION["user"]["isadministrator"]){
				$_POST['login'] = trim($_POST['login']);
				$_POST['password'] = trim($_POST['password']);
				$_POST['password_confirm'] = trim($_POST['password_confirm']);
				
				if( !empty($_POST['login'])&&$_POST['password']==$_POST['password_confirm'] ){
					unset($_POST["delete"]);
					unset($_POST['password_confirm']);
					
					if( $id_current>0 ){	// Modification
						// Vérification de doublons
						$PDOStatement = $IagoDB->prepare("SELECT COUNT(*) AS doublons FROM `$page_current` WHERE `login`='{$_POST["login"]}' AND `id`!='$id_current'");
						$PDOStatement->execute();
						$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
						
						if( empty($results["doublons"]) ){	// Pas de doublons						
							$sql.= "UPDATE `$page_current` SET ";
							$sql.= " `lastname`='{$_POST["lastname"]}',";
							$sql.= " `firstname`='{$_POST["firstname"]}',";
							$sql.= " `login`='{$_POST["login"]}',";
							if( !empty($_POST["password"]) ){
								$sql.= " `password`='".md5($_POST["password"])."',";
							}
							$sql.= " `isadministrator`='".((int)isset($_POST["isadministrator"]))."'";
							$sql.= " WHERE `id`='$id_current';";
							
							// L'utilisateur modifié est celui connecté
							if( $_SESSION["user"]["login"]==$_POST["login"] ){
								$_SESSION["user"]["isadministrator"] = (int)isset($_POST["isadministrator"]);
							}
						}
					}
				}
			}
		break;
		
		// Ajout d'un utilisateur
		case "add":
			if($_SESSION["user"]["isadministrator"]){
				$_POST[$action."-login"] = trim($_POST[$action."-login"]);
				$_POST[$action."-password"] = trim($_POST[$action."-password"]);
				$_POST[$action."-password_confirm"] = trim($_POST[$action."-password_confirm"]);
				
				if( !empty($_POST[$action."-login"])&&$_POST[$action."-password"]==$_POST[$action."-password_confirm"] ){
					unset($_POST[$action."-password_confirm"]);
					
					// Vérification de doublons
					$PDOStatement = $IagoDB->prepare("SELECT COUNT(*) AS doublons FROM `$page_current` WHERE `login`='{$_POST[$action."-login"]}'");
					$PDOStatement->execute();
					$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
					
					if( empty($results["doublons"]) ){	// Pas de doublons						
						$sql.= "INSERT INTO `$page_current` (`lastname`,`firstname`,`login`,`password`,`isadministrator`) ";
						$sql.= "VALUES ('{$_POST[$action."-lastname"]}','{$_POST[$action."-firstname"]}','{$_POST[$action."-login"]}','".md5($_POST[$action."-password"])."','".((int)isset($_POST[$action."-isadministrator"]))."');";
						$PDOStatement = $IagoDB->prepare($sql);
						$PDOStatement->execute();
					}
				}
			}
					
			$sql = "SELECT id,lastname,firstname,login,isadministrator FROM $page_current";	
		break;
		
		// Recherche d'utilisateur
		case "search":
		case "cancel":
			unset($_POST["delete"]);
			
			$sql.= "SELECT id,lastname,firstname,login,isadministrator FROM $page_current";
	
			$where = array();
			foreach($_POST as $field_name=>$field_value){
				$field_value = trim($field_value);
				if( !empty($field_value) ){
					$where[] = "$page_current.$field_name LIKE '%$field_value%'";
				}
			}
			if( !empty($where) ){
				$sql.= " WHERE ".implode(" AND ",$where);
			}
		break;
	}
		
	if( !empty($sql) ){
		$PDOStatement = $IagoDB->prepare($sql);
		$PDOStatement->execute();
	}
?>