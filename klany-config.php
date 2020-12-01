<?


require('include/smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->force_compile = true;
//$smarty->debugging = true;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

	
	require_once('config.php');
	require_once('include/admin/funkcje/function.table.php');
	require_once('include/clans/func.php');
	
	define("KLAN_R_ID",2);
	define("admsg_tableIsEmtpy","brak danych w tabeli");
	define("BASE_URL","/pesarena/klany/");
	define("FURL","http://stopczynski.pl/pesarena/");
	
	define ("FIRST_HTACCESS_VAR","opt");
	define ("SECOND_HTACCESS_VAR","round");
	define ("THIRD_HTACCESS_VAR","addopt");
	
	define("ACCESS",$_SESSION['klany_access']); // 1 - admin, 2 - kapitan 3 - gracz
	

	define("CAPTAINS_CLAN",$_SESSION['klany_kapitan_id']);

	
	$loadConfigFile = array(1 => "clan.api-admin.php",2 => "clan.api-capitan.php",3 => "clan.api-capitan.php");

	require_once("include/clans/messages.php");
	foreach ($clans as $keyToDefine => $valueToDefine )	{		define('clans_'.$keyToDefine,$valueToDefine);	}
	
	//echo kodowanie_loginu('lol')."<br/>".kodowanie_hasla('wow');

	
?>