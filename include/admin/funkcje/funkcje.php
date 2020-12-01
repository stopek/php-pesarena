<?


function formularz_podaj_id($name)
{
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">podaj identyfikator szukanego spotkania</li>
		<li class=\"glowne_bloki_zawartosc\">
		<form method=\"post\" action=\"\">
			<input type=\"text\" name=\"{$name}\"/>
			<input type=\"hidden\" name=\"wy\" value=\"podaj\"/>

			
			<input type=\"image\" alt=\"\" src=\"img/_admin_wykonaj_.jpg\"/>
		</form>
		</li>
	</ul>";
}


function jaka_faza_gry_nastepna($aktualna)
{
    switch ($aktualna) {
        case '1_16':
            $c = '1_8';
            break;
        case '1_8':
            $c = '1_4';
            break;
        case '1_4':
            $c = '1_2';
            break;
        case '1_2':
            $c = '1_1';
            break;
        default:
            $c = false;
            break;
    }
    return $c;
}

function wybor_gry_admin($opcja)
{

    echo "
		<div class=\"versionPanel\">
			<ul>";
    if (empty($_GET['wybrana_gra'])) {
        $sql = mysql_query("SELECT * FROM " . TABELA_GAME);
        while ($rek = mysql_fetch_array($sql)) {
            echo "<li>
								<span><a href=\"" . AKT . "&wybrana_gra={$rek['skrot']}\">{$rek['nazwa']}({$rek['skrot']})</a></span>
								<em><a href=\"" . AKT . "&wybrana_gra={$rek['skrot']}\"></a></em>
							</li>";
        }
    }
    echo "
			</ul>
			<div class=\"header\"><span><a href=\"administrator.php?opcja={$_GET['opcja']}\">Wroc do wyboru wersji gry</a></span></div>
		</div>
	";
}

function sprawdz_dostep_admina($kto, $co)
{
    $as2 = mysql_fetch_array(mysql_query("Select * from " . TABELA_ADMINI . " where login='{$kto}';"));
    $array_access = explode(',', $as2['access']);
    $array_access_game = explode(',', $as2['vliga']);
    if ($co == 'poziom') {
        return $array_access;
    } elseif ($co == 'liga') {
        return $array_access_game;
    } elseif ($co == '1') {
        return $as2['access'];
    } elseif ($co == '2') {
        return $as2['vliga'];
    }
}


?>
