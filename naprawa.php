<?require_once('config.php');


$n = 39;

$s = mysql_query(" SELECT * FROM liga_gracze WHERE r_id = '{$n}'");

while ($r = mysql_fetch_array($s))
{
	$m = mysql_fetch_array(mysql_query("SELECT nr_ligi FROM liga WHERE r_id = '{$n}' && n1 = '{$r['id_gracza']}' "));
	$i++;
	echo $i.".".sprawdz_login_id($r['id_gracza'])." [{$m['nr_ligi']}]<br/>";
	
	mysql_query("UPDATE liga_gracze SET status = '{$m['nr_ligi']}'  WHERE r_id = '{$n}' && id_gracza = '{$r['id_gracza']}'");
}







?>


