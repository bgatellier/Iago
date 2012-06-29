<?php
	$sql = "";
	
	switch($action){
		// Suppression du logiciel	
		case "delete":
			if($_SESSION["user"]["isadministrator"]){
				// Suppression du logiciel
				$PDOStatement = $IagoDB->prepare("DELETE FROM $page_current WHERE id='{$_POST["delete"]}'");
				$PDOStatement->execute();
				
				// Suppression du logiciel de l'ensemble des parcs
				$PDOStatement = $IagoDB->prepare("DELETE FROM software_park WHERE id_software='{$_POST["delete"]}'");
				$PDOStatement->execute();
				
				$sql.= "SELECT id,name,versionname,versionnumber,description FROM $page_current";
			}
		break;
		
		// Suppression du lien entre le logiciel et le parc
		case "deletePS":
			if($_SESSION["user"]["isadministrator"]){
				$sql.= "DELETE FROM software_park WHERE id='{$_POST["delete"]}'";
			}
		break;
			
		// Ajout / Modification du logiciel
		case "edit":
			if($_SESSION["user"]["isadministrator"]){
				unset($_POST["delete"]);
				
				$_POST["name"] = trim($_POST["name"]);
				$_POST["versionname"] = trim($_POST["versionname"]);
				
				if( !empty($_POST["name"])&&!empty($_POST["versionname"]) ){
					if( $id_current>0 ){	// Modification
						// Vérification de l'existance d'un logiciel similaire, hormis celui qui vient d'être modifié
						$PDOStatement = $IagoDB->prepare("SELECT COUNT(*) AS doublons FROM `$page_current` WHERE `name`='{$_POST["name"]}' AND `versionname`='{$_POST["versionname"]}' AND `id`!='$id_current'");
						$PDOStatement->execute();
						$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
						
						if( empty($results["doublons"]) ){	// Pas de doublons
							$sql.= "UPDATE `$page_current` SET ";
							$sql.= "`name`='{$_POST["name"]}',";
							$sql.= "`versionname`='{$_POST["versionname"]}',";
							$sql.= "`versionnumber`='{$_POST["versionnumber"]}',";
							$sql.= "`description`='{$_POST["description"]}'";
							$sql.= " WHERE `id`='$id_current';";
						}
					}
				}
			}
		break;
		
		// Ajout d'un logiciel
		case "add":
			if($_SESSION["user"]["isadministrator"]){
				$_POST[$action."-name"] = trim($_POST[$action."-name"]);
				$_POST[$action."-versionname"] = trim($_POST[$action."-versionname"]);
				
				if( !empty($_POST[$action."-name"])&&!empty($_POST[$action."-versionname"]) ){
					// Vérification de l'existance d'un logiciel similaire
					$PDOStatement = $IagoDB->prepare("SELECT COUNT(*) AS doublons FROM `$page_current` WHERE `name`='{$_POST[$action."-name"]}' AND `versionname`='{$_POST[$action."-versionname"]}'");
					$PDOStatement->execute();
					$results = $PDOStatement->fetch(PDO::FETCH_ASSOC);
					
					if( empty($results["doublons"]) ){	// Pas de doublons
						$sql.= "INSERT INTO `$page_current` (`name`,`versionname`,`versionnumber`,`description`) ";
						$sql.= "VALUES ('{$_POST[$action."-name"]}','{$_POST[$action."-versionname"]}','{$_POST[$action."-versionnumber"]}','{$_POST[$action."-description"]}');";
						$PDOStatement = $IagoDB->prepare($sql);
						$PDOStatement->execute();
					}
				}
			}
			
			$sql = "SELECT id,name,versionname,versionnumber,description FROM $page_current";
		break;
		
		// Recherche de logiciel
		case "search":
		case "cancel":
			unset($_POST["delete"]);
			
			$sql.= "SELECT id,name,versionname,versionnumber,description FROM $page_current";
			
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
		
		// Affectation d'un park au logiciel
		case "software":
			if($_SESSION["user"]["isadministrator"]){
				$sql.= "INSERT INTO software_park(`id_software`,`id_park`) VALUE('$id_current','{$_POST["id_park"]}')";
			}
		break;
	}
	
	if( !empty($sql) ){
		$PDOStatement = $IagoDB->prepare($sql);
		$PDOStatement->execute();
	}
?>