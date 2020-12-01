<?


function end_table()
{
    print "</table><br/>";
}

function center()
{
    print "<center>";
}

function ecenter()
{
    print "</center>";
}

function start_table()
{
    print "<table border=\"1\" frame=\"void\">";
}

function przykladowy_wynik($id)
{
    print "<tr  class=\"text_center\"><td colspan=\"2\"><input type=\"text\"  name=\"przykladowy_wynik_{$id}\" onkeyup=\"pokaz(this.form);\" size=\"7\"/></td></tr>";
}


function nazwa_gracza($id)
{
    print "<tr class=\"text_center\">
		<td colspan=\"2\">" . linkownik('profil', $id, '') . "</td>
	</tr>";
}

function logo_druzyny($id, $druzyna)
{
    $dru = sprawdz_nazwe_klubu($druzyna);
    $dr = "grafiki/loga/" . $dru;
    if (empty($dru)) {
        $dr = $druzyna;
    }
    print "<tr  class=\"text_center\">
		<td colspan=\"2\"><img src=\"" . $dr . "\" id=\"logo_druzyny_{$id}\" alt=\"\" title=\"\"/></td>
	</tr>";
}

function nowy_status_pkt($id)
{
    print "<tr>
		<td>" . M349 . "</td>
		<td>
		<input type=\"text\"  disabled class=\"disabled\"  name=\"nowy_status_pkt_{$id}\" size=\"7\"/>
		</td>
	</tr>";
}

function bonus($id)
{
    print "<tr>
	<td>" . M350 . "</td>
	<td><input type=\"text\"  disabled class=\"disabled\" name=\"bonus_{$id}\" size=\"7\"/></td>
</tr>";
}

function pkt_z_druzyne($id)
{
    print "<tr>
		<td>" . M351 . "</td>
		<td><input type=\"text\"  disabled class=\"disabled\"  name=\"pkt_z_druzyne_{$id}\" size=\"7\"/></td>

	</tr>";
}

function pkt_za_mecz($id)
{
    print "<tr>
		<td>" . M352 . "</td>
		<td><input type=\"text\"  disabled class=\"disabled\"  name=\"pkt_za_mecz_{$id}\" size=\"7\"/></td>
	</tr>";
}

function pamiec_pkt($pkt)
{
    print "<tr>
		<td>" . M353 . "</td>
		<td>{$pkt}</td>
	</tr>";
}

function aktualne_miejsce($miejsce)
{
    print "<tr>
		<td>" . M354 . "</td>
		<td>{$miejsce}</td>
	</tr>";
}

function naglowek_meczu($kto)
{
    print "<tr class=\"naglowek\">
		<td colspan=\"2\"  class=\"text_center\">{$kto}</td>
	</tr>";
}

function moja_druzyna($nazwa)
{
    print "<tr class=\"naglowek\" >
		<td colspan=\"2\" class=\"text_center\" >{$nazwa}</td>
	</tr>";
}

?>
