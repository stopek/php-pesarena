<?
$zamien = array(
    TABELA_LIGA => 1,
    TABELA_PUCHAR_DNIA => 2,
    TABELA_WYZWANIA => 3,
    TABELA_TURNIEJ => 4);

$jakie = array(
    TABELA_LIGA => 'ligowe',
    TABELA_PUCHAR_DNIA => 'pucharowe',
    TABELA_WYZWANIA => 'towarzystkie',
    TABELA_TURNIEJ => 'turniejowe');

?>


<script type="text/javascript">
    window.addEvents({
        'domready': function () {
            /* thumbnails example , div containers */
            new SlideItMoo({
                overallContainer: 'SlideItMoo_outer_<?=$zamien[$tabela]?>',
                elementScrolled: 'SlideItMoo_inner_<?=$zamien[$tabela]?>',
                thumbsContainer: 'SlideItMoo_items_<?=$zamien[$tabela]?>',
                itemsVisible: 1,
                elemsSlide: 1,
                duration: 300,
                itemsSelector: '.SlideItMoo_element_<?=$zamien[$tabela]?>',
                itemWidth: 220,
                showControls: 1,
                onChange: function (index) {
                    //alert(index);
                }

            });
        }
    });
</script>


<div id="SlideItMoo_outer_<?= $zamien[$tabela] ?>">
    <div id="SlideItMoo_inner_<?= $zamien[$tabela] ?>">
        <div id="SlideItMoo_items_<?= $zamien[$tabela] ?>">
            <?
            $b = 0;
            $wynik = mysql_query("SELECT * FROM  {$tabela} WHERE status='3' && vliga='" . DEFINIOWANA_GRA . "'  ORDER BY `id` DESC LIMIT 0,1;");
            if (mysql_num_rows($wynik) == 0) {
                note(M206, 'blad');
            }
            while ($rekord = mysql_fetch_array($wynik)) {
                $b++;
                echo "<div class=\"SlideItMoo_element_{$zamien[$tabela]}\"><div class=\"ostatni_mecz\">
							<div class=\"ostatni_mecz_lewa\">
								<div class=\"ostatni_mecz_lewa_lewa\">" . linkownik('profil', $rekord['n1'], '') . "(" . $rekord['k_1'] . ")
									<img class=\"last_match_image\" src=\"grafiki/loga/" . sprawdz_nazwe_klubu($rekord['klub_1']) . "\" 
									alt=\"\" title=\"\"/><br/>" . linkownik('profil_druzyny', $rekord['klub_1'], '') . "
								</div>
								<div class=\"ostatni_mecz_lewa_prawa\">
									<img src=\"grafiki/cyfry/{$rekord['w1']}.png\"  alt=\" {$rekord['w1']} \"/>
								</div>
							</div>
							<div class=\"ostatni_mecz_srodek\"><img src=\"grafiki/cyfry/dwukropek.png\" alt=\" : \"/></div>
							<div class=\"ostatni_mecz_prawa\">
								<div class=\"ostatni_mecz_prawa_lewa\">
									<img src=\"grafiki/cyfry/{$rekord['w2']}.png\"  alt=\" {$rekord['w2']} \" />
								</div>
								<div class=\"ostatni_mecz_prawa_prawa\">
									" . linkownik('profil', $rekord['n2'], '') . "({$rekord['k_2']})
									<img  class=\"last_match_image\"  src=\"grafiki/loga/" . sprawdz_nazwe_klubu($rekord['klub_2']) . "\" 
									alt=\"\" title=\"\"/><br/>" . linkownik('profil_druzyny', $rekord['klub_2'], '') . "
								</div>
							</div>
						</div><div class=\"no_match_info\"></div></div>";
            }

            ?>
        </div>
    </div>
</div>
