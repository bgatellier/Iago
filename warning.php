<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>Confirmation</title>
</head>
<?php
	include_once("connect.php");
	
	$form_submit_delete_name = "delete-submit";
	$id_to_delete = $_GET["id"];
	if( isset($_GET["subid"]) ){
		$form_submit_delete_name .= "PS";
		$id_to_delete = $_GET["subid"];
		unset( $_GET["subid"] );
	}
	
	// Construction de l'URL de retour du formulaire
	$form_url = "";
	foreach ($_GET as $arg=>$value){
		$form_url.= "&amp;$arg=$value";
	}
	$form_url = "index.php?".substr($form_url,5);
?>
<body>
	<form action="<?php echo $form_url;?>" method="post">
		<?php include_once("content/{$_GET["page"]}/warning.php");?>
		<p><input name="delete" type="hidden" value="<?php echo $id_to_delete;?>"/></p>
		<p class="warning-popup-actions">
			<button name="<?php echo $form_submit_delete_name;?>" type="submit"><img alt="" src="pic/tick.png"/>Confirmer</button>
			<button name="submit_cancel" type="submit"><img alt="" src="pic/cross.png"/>Annuler</button>
		</p>
	</form>
</body>
</html>