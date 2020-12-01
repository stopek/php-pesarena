<?


$data = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_ADMINI . " WHERE login='{$szefuniu}'"));

echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">Informacje</li>
		<li class=\"glowne_bloki_zawartosc\">
			<fieldset><legend>Ostatnio logowales sie (data)</legend> " . ($_SESSION['admin_last'][0] == "0000-00-00 00:00:00" ? "jestes tutaj pierwszy raz" : $_SESSION['admin_last'][0]) . "</fieldset>
			<fieldset><legend>Ostatnio logowales sie z ip</legend> {$_SESSION['admin_last'][1]}</fieldset>
			<fieldset><legend>Ostatnio logowales sie na hoscie</legend> {$_SESSION['admin_last'][2]}</fieldset>
			<fieldset><legend>Twoje IP</legend> {$_SERVER['REMOTE_ADDR']}</fieldset>
			<fieldset><legend>Twoj HOST</legend> {$host}</fieldset>
			<fieldset><legend>Twoja przegladarka</legend> " . nazwij_przegladarke($_SERVER['HTTP_USER_AGENT']) . "</fieldset>
			<fieldset><legend>Lacznie zalogowan</legend> {$data['counter']}</fieldset>
			<fieldset><legend>JavaScript</legend> 
				
					<noscript>Twoja przegladarka ma <b>wylaczona obsluge JavaScript</b>. Mozesz miec problemy, gdyz czesc panelu 
					administracyjnego wymaga wlaczonej obslugi JavaScript'u</noscript>
					<script language=\"JavaScript\">
					document.write(\"<strong>Twoja przegladarka uzywa JavaScript</strong>\")
					</script>
				
			</fieldset>
			<fieldset><legend>Twoje dane</legend>
				<fieldset>
					<img src=\"grafiki/loga/{$data['druzyna']}.png\"  style=\"margin:20px; float:left;\" alt=\"\"/>
					<div class=\"admin_info\">
						<ul>
							<li>Login/Nick : {$data['login']}/" . sprawdz_login_id($data['nick']) . "</li>
							<li>Mail : {$data['mail']}</li>
							<li>GG : {$data['gg']}</li>
							<li>O sobie : {$data['osobie']}</li>
							<li>Stanowisko : {$data['stanowisko']}</li>
						</ul>
					</div>
				</fieldset>
			</fieldset>
		</li>
	</ul>";
?>

<?


echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">Witaj {$szefuniu} twoje prawa dostepu to</li>
		<li class=\"glowne_bloki_zawartosc\">";
foreach ($wybory as $key => $value) {
    echo "<fieldset class=\"admin_access_prof\">
					<legend><strong>{$key}</strong></legend>
					<span>{$value}</span><img src=\"grafiki/admin_ikons/admin_access_" . (in_array($key, $poziom_u_a) ? "ok" : "no") . ".png\" alt=\"\"/>
				</fieldset>";
    $all_rights++;
    if (in_array($key, $poziom_u_a)) {
        $rights++;
    }
}
$procent = round(($rights / $all_rights) * 100, 2);
echo "<fieldset class=\"admin_access_prof\">
					<legend>Podsumowanie</legend>
					<span>
						Lacznie na stronie jest {$all_rights} poziomow dostepu <br/>
						Ty posiadasz <strong>{$rights}</strong> prawa dostepu a wiec masz dostep do <strong>{$procent}%</strong> panelu administracyjnego
					</span>
			</fieldset>";

echo "</li>
	</ul>";


?>