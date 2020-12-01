<?

//wyswietla dodatkowe punkty gracza np. w profilu (ukryte)
function wyswietl_dodatkowe_punkty($typ)
{

    $change = array("+" => " && punkty>'0' ", "-" => " && punkty<'0' ", "0" => "");
    $sql = mysql_query("SELECT * FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE vliga='" . DEFINIOWANA_GRA . "' {$change[$typ]} 
	ORDER BY `wystawiono` DESC LIMIT " . LIMIT_DODATKOWE_PUNKTY_NA_STRONIE);
    echo "<table border=0 width=100%>";
    while ($rek = mysql_fetch_array($sql)) {
        echo "<tr" . kolor($a++ . '_dodatkowe_' . $typ) . ">
			<td>" . linkownik('profil', $rek['id_gracza'], '') . "</td>
			<td>{$rek['opis']}</td>
			<td>{$rek['punkty']}pkt.</td>
		</tr>";
    }
    echo "</table>";

} //wyswietla dodatkowe punkty gracza np. w profilu (ukryte) - END


function wyswietl_wszystkie_dodatkowe_punkty()
{
    $na_stronie = 30;
    $podmenu = (int)$_GET['podmenu'];
    if (!$podmenu) {
        $podmenu = 1;
    }
    $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE vliga='" . DEFINIOWANA_GRA . "' 
	ORDER BY `wystawiono` DESC"));
    $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);
    $start = ($podmenu - 1) * $na_stronie;

    $sum = mysql_fetch_array(mysql_query("SELECT SUM(punkty),COUNT(punkty) FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE vliga='" . DEFINIOWANA_GRA . "' GROUP BY 'punkty'"));
    note("Dodatkowe punkty wystawiono: <b>{$sum[1]}</b> razy o lacznej wartosc <b>{$sum[0]}</b> punktow! ", "fieldset");


    $sql = mysql_query("SELECT * FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE vliga='" . DEFINIOWANA_GRA . "' 
	ORDER BY `wystawiono` DESC LIMIT {$start},{$na_stronie}");
    naglowek_wszystkie_dodatkowe_punkty();
    while ($rek = mysql_fetch_array($sql)) {
        //wyswietlam zawartosc wszystkich dodatkoweych punktow
        //jesli w sesji zmienna ACCESS_ADMIN istnieje - pokazuje linki dla admina
        echo "<tr" . kolor($a++ . '_dodatkowe_wszystkie') . ">
			<td>" . linkownik('profil', $rek['id_gracza'], '') . "</td>
			<td>{$rek['opis']}</td>
			<td>{$rek['punkty']}pkt.</td>
			<td>" . formatuj_date($rek['wystawiono']) . "</td>
			" . (defined('SHOW_LINKS') ? "<td>
				<a href=\"" . AKT . "&edit={$rek['id']}\" class=\"i-edit\"></a>
				<a href=\"" . AKT . "&delete={$rek['id']}\" onclick=\"return confirm('Czy jestes pewien, ze chesz usunac te dodtkowe punkty?');\" class=\"i-delete\"></a>
			</td>" : "") . "
		</tr>";
    }
    echo "</table>";
    if (isset($_SESSION['ACCESS_ADMIN'])) {
        wyswietl_stronnicowanie($podmenu, $wszystkie_strony, AKT . "&podmenu=", '');
    } else {
        wyswietl_stronnicowanie($podmenu, $wszystkie_strony, 'dodatkowe,punkty-', '.htm');
    }
} //wyswietla dodatkowe punkty gracza np. w profilu (ukryte) - END
?>
