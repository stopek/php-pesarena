<?php require("config.php"); 

$action 				= $_POST['action'];
$updateRecordsArray 	= $_POST['recordsArray'];

if ($action == "updateRecordsListings"){
	
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) 
	{
		mysql_query("UPDATE ".BLOKI_MENU_PRZYPISANIE." SET kolejnosc = " . $listingCounter . " WHERE id = " . $recordIDValue) or die('Error, insert query failed');
		$listingCounter = $listingCounter + 1;	
	}
}
?>