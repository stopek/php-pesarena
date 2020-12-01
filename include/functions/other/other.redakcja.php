<?
// wyswietla czlonkow redakcji (opiekunow)
function wyswietl_redakcje()
{
    echo "<fieldset>";

    $sql = mysql_query("SELECT * FROM admini");
    while ($rekord = mysql_fetch_array($sql)) {
        echo '
	<div class="profil_ksiazki">
		
		<fieldset>
			<fieldset class="profil_image_fieldset">
				<img src="grafiki/loga/' . $rekord['druzyna'] . '.png"  alt="' . M288 . '"/>
			</fieldset>
				<div class="profil_pasek_jasny"><b>' . M138 . ':</b> ' . linkownik('profil', $rekord['nick'], '') . '</div>
				<div class="profil_pasek_ciemny"><b>' . M558 . ':</b> ' . $rekord['stanowisko'] . '</div>
				<div class="profil_pasek_jasny"><b>' . M82 . ':</b> <a href="mailto:' . $rekord['mail'] . '">' . $rekord['mail'] . '</a></div>
				<div class="profil_pasek_ciemny"><b>' . M81 . '</b> ' . $rekord['gg'] . '</div>
				<div class="profil_pasek_jasny"><b>' . M559 . '</b> ' . $rekord['osobie'] . '</div>
		</fieldset>
		
	</div>';

    }
    echo "</fieldset>";
} // wyswietla czlonkow redakcji (opiekunow) - END
?>
