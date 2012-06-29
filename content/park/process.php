<?php
	$sql = "";
	
	switch($action){
		// Suppression du parc
		case "delete":
			if($_SESSION["user"]["isadministrator"]){
				// Récupération de l'ID du parc parent
				$PDOStatement = $IagoDB->prepare("SELECT id_parent FROM $page_current WHERE id='{$_POST["delete"]}'");
				$PDOStatement->execute();
				$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
			
				// Mise à jour des parcs enfants
				$PDOStatement = $IagoDB->prepare("UPDATE park SET id_parent='{$results["id_parent"]}' WHERE id_parent='{$_POST["delete"]}'");
				$PDOStatement->execute();
				
				// Suppression des logiciels du parc
				$PDOStatement = $IagoDB->prepare("DELETE FROM sofware_park WHERE id_park='{$_POST["delete"]}'");
				$PDOStatement->execute();
				
				// Suppression du parc
				$PDOStatement = $IagoDB->prepare("DELETE FROM $page_current WHERE id='{$_POST["delete"]}'");
				$PDOStatement->execute();
				
				$sql.= "SELECT park.id, park.name AS parkname, park_parent.name AS parkname_parent
							FROM $page_current
							LEFT JOIN $page_current AS park_parent ON park.id_parent=park_parent.id";
			}
		break;
		
		// Suppression du lien entre le parc et le logiciel
		case "deletePS":
			if($_SESSION["user"]["isadministrator"]){
				$sql.= "DELETE FROM software_park WHERE id='{$_POST["delete"]}'";
			}
		break;
		
		// Modification du parc
		case "edit":
			if($_SESSION["user"]["isadministrator"]){
				unset($_POST["delete"]);
				$_POST["name"] = trim($_POST["name"]);
				
				if( !empty($_POST["name"]) ){
					if( $id_current>0 ){	// Modification
						// Vérification de l'existence de doublons
						$PDOStatement = $IagoDB->prepare("SELECT COUNT(*) AS doublons FROM `$page_current` WHERE `name`='{$_POST["name"]}' AND `id`!='$id_current'");
						$PDOStatement->execute();
						$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
						
						if( empty($results["doublons"]) ){	// Pas de doublons
							$sql.= "UPDATE `$page_current` SET";
							$sql.= " `id_parent`='{$_POST["id_parent"]}',";
							$sql.= " `name`='{$_POST["name"]}'";
							$sql.= " WHERE `id`='$id_current';";
						}
					}
				}
			}
		break;
		
		// Ajout d'un parc
		case "add":
			if($_SESSION["user"]["isadministrator"]){
				$_POST[$action."-name"] = trim($_POST[$action."-name"]);
					print_r("test1");
				
				if( !empty($_POST[$action."-name"]) ){
					print_r("test2");
					// Vérification de l'existance de doublons
					$PDOStatement = $IagoDB->prepare("SELECT COUNT(*) AS doublons FROM `$page_current` WHERE name='{$_POST[$action."-name"]}'");
					$PDOStatement->execute();
					$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
					
					if( empty($results["doublons"]) ){	// Pas de doublons
						$sql.= "INSERT INTO `$page_current` (`id_parent`,`name`) ";
						$sql.= "VALUES ('{$_POST[$action."-id_parent"]}','{$_POST[$action."-name"]}');";
						$PDOStatement = $IagoDB->prepare($sql);
						$PDOStatement->execute();
					}
				}
			}
			
			$sql = "SELECT park.id, park.name AS parkname, park_parent.name AS parkname_parent
						FROM $page_current
						LEFT JOIN $page_current AS park_parent ON park.id_parent=park_parent.id";
		break;
		
		// Recherche de parc
		case "search":
		case "cancel":
			unset($_POST["delete"]);
			
			$sql.= "SELECT park.id, park.name AS parkname, park_parent.name AS parkname_parent
						FROM $page_current
						LEFT JOIN $page_current AS park_parent ON park.id_parent=park_parent.id";
			foreach($_POST as $field_name=>$field_value){
				$field_value = trim($field_value);
				if( !empty($field_value) ){
					$sql.= " AND $page_current.$field_name LIKE '%$field_value%'";
				}
			}
		break;
		
		// Affectation d'un logiciel au park
		case "software":
			if($_SESSION["user"]["isadministrator"]){
				$sql.= "INSERT INTO software_park(`id_software`,`id_park`) VALUE('{$_POST["id_software"]}','$id_current')";
			}
		break;
	}
			
	if( !empty($sql) ){
		$PDOStatement = $IagoDB->prepare($sql);
		$PDOStatement->execute();
	}
?>