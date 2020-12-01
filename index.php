<?php

include("config.php");
define_index((int)$_SESSION['sesja_liga'], (int)$_SESSION['sesja_puchar'], (int)$_SESSION['sesja_turniej']);


if ($_GET['get'] == 'mail') {


    $query = mysql_query("SELECT * FROM uzytkownicy");

    while ($r = mysql_fetch_array($query)) {
        echo ($_GET['pseudo'] == 1 ? $r['pseudo'] . " - " : '') . $r['mail'] . "<Br/>";
    }

    exit;
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title><?= $naglowek_title ?></title>
    <meta name="Description" content="Polski Serwis Pro Evolution Soccer 2009, 2008, 6, 5, 4, ISS"/>
    <meta name="Keywords"
          content="puchar, turniej, mistrzostwa ¶wiata, master league, konami, bramka, gol, pesarena, stopekA, liga, drużyna, klub, pes4, pro evolution soccer, liga mistrzów, pesluncher, ranking, zawodnik, piłkarz, bramkarz, sędzia, spalony, faul, faza eliminacyjna, wyzwania, sezon, tabela, wyniki, spotkanie, gracz miesi±ca, tygodnia,dnia, poker rank, imprezy, punkty, liga pes, pes league, puchar dnia, turniej, pro evolution soccer"/>
    <meta name="Author" content="Paweł Stopczyński"/>
    <meta name="Robots" content="index, follow"/>
    <meta name="Googlebot" content="index, follow"/>

    <link rel="stylesheet" type="text/css" href="style.css" media="all"/>
    <link rel="stylesheet" href="css/jquery.tabs.css" type="text/css" media="print, projection, screen">
    <!-- Additional IE/Win specific style sheet (Conditional Comments) -->
    <!--[if lte IE 7]>
    <link rel="stylesheet" href="css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
    <![endif]-->

    <script type="text/javascript" src="js/stmenu.js"></script>
    <script type="text/javascript" src="js/skrypty.js"></script>

    <script type="text/javascript" src="js/slide/mootools-1.2.1-core.js"></script>
    <script type="text/javascript" src="js/slide/mootools-1.2-more.js"></script>
    <script type="text/javascript" src="js/slide/slideitmoo-1.1.js"></script>

    <script src="js/mootools.js" type="text/javascript"></script>
    <script src="js/sexyalert/sexyalertbox.v1.js" type="text/javascript"></script>
    <script src="js/sexyalert/sexyalert.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/dragdrop/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/dragdrop/jquery-ui-1.7.1.custom.min.js"></script>
    <script type="text/javascript" src="js/dragdrop/dragdrop.js"></script>

    <script src="js/tabs/jquery-1.1.3.1.pack.js" type="text/javascript"></script>
    <script src="js/tabs/jquery.history_remote.pack.js" type="text/javascript"></script>
    <script src="js/tabs/jquery.tabs.pack.js" type="text/javascript"></script>
    <script type="text/javascript">
        var $j = jQuery.noConflict();
        $j(function () {
            $j('.jquery_menu').tabs({fxSlide: true, fxFade: true, fxSpeed: 'normal'});
        });


    </script>
    <script language="JavaScript">
        function otworz(adres) {
            noweOkno = window.open(adres, 'okienko', ' width=1300, height=600')
            noweOkno.focus()
        }

    </script>


    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-26480715-2']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>
    <script type="text/javascript" src="jquery-latest.js"></script>

    <script type="text/javascript">

        var $jaa = jQuery.noConflict();
        $jaa(document).ready(function () {

            var val = $jaa("#wait span").html();
            setTimeout(function () {
                $jaa("#wait").html(val);
            }, 15000);
        });


    </script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-18088458-1']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>


</head>
<? // note("Przerwa techniczna. Wracamy przed 10:00","info"); exit; ?>
<body onload="start();">
<style> body {
        background: #464646;
    }</style>


<div id="shadow">
    <div id="page">


        <ul class="ul_menu_specific">
            <li class="forum"><a href="http://peselite.pl/mirc/" target="_blank">MIRC!</a></li>
            <li class="-punktor"><?= M582 ?>:</li>
            <li><a href="http://proevolutionsoccer-4.pl.tl/FAQ.htm" title="<?= M592 ?>">FAQ</a></li>
            <li><a href="http://www.pesarena.com.pl/forum/viewforum.php?f=89" title="<?= M591 ?>"><?= M583 ?></a></li>
            <li><a href="http://www.pesarena.com.pl/forum/viewforum.php?f=51" title="<?= M590 ?>"><?= M584 ?></a></li>
            <li><a href="rejestracja.htm" title="<?= M593 ?>"><?= M585 ?></a></li>
            <li><a href="logowanie.htm" title="<?= M589 ?>"><?= M586 ?></a></li>
            <li class="forum"><a href="forum/" title="<?= M588 ?>"><?= M442 ?></a></li>
            <li class="forum"><a href="/klany/" target="_blank">system klanow</a></li>

        </ul>
        <div id="top">
            <div id="user_panel">
                <ul>
                    <li><a href="javascript:rozwin('panel_usera');"><?= M587 ?></a></li>
                    <li><a href="profil,edytuj.htm" title="<?= M209 ?>"><?= M209 ?></a></li>
                    <li><a href="ranking.htm" title="<?= M210 ?>"><?= M210 ?></a></li>
                </ul>
            </div>
            <div id="panel_usera"><? user_panel_link($opis_gry[$l_u][0]); ?></div>
        </div>


        <div id="menu_glowne">
            <script type="text/javascript" src="language/menu-pl.js?v=2"></script>
        </div>

        <!-- blok z ostatnimi spotkaniami -->
        <div class="naglowek_partnerzy">
            <div class="zaokraglenie_lewa"></div>
            <img src="img/strzalka.gif" alt="<?= M554 ?>"/>
            <h1><?= M554 ?></h1>
            <div class="zaokraglenie_prawa"></div>
        </div>
        <div class="nasi_partnerzy_srodek"><? lista_spotkan(); ?>
            <div style="float:left;">

                <script type="text/javascript"><!--
                    google_ad_client = "ca-pub-2424219527824461";
                    /* pesarena.pl - dlugi banner pod l. rozgrywek */
                    google_ad_slot = "5764651764";
                    google_ad_width = 728;
                    google_ad_height = 90;
                    //-->
                </script>
                <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>


            </div>
        </div>
        <div class="buttony_koniec">
            <div class="zaokraglenie_lewa_dol"></div>
            <div class="zaokraglenie_prawa_dol"></div>
        </div>
        <!-- blok z ostatnimi spotkaniami - koniec -->


        <div id="srodek">


            <!-- blok z newsami -->
            <div class="srodek_news_gora">
                <div class="zaokraglenie_lewa"></div>
                <img src="img/strzalka.gif" alt="<?= M217 ?>"/>
                <span style="padding-top:5px; font:13px verdana; color:white;"><?= M217 ?></span>
                <div class="zaokraglenie_prawa"></div>
            </div>

            <? dodatkowe_zakladki(); ?>
            <div class="srodek_news_tlo">
                <? include("include/srodek.php"); ?>

            </div>
            <? wyswietl_archiwum($opcja, $podmenu); ?>
            <div class="archiwum_koniec">
                <div class="zaokraglenie_lewa_dol"></div>
                <div class="zaokraglenie_prawa_dol"></div>
            </div>
            <!-- blok z newsami - koniec -->


        </div>


        <div id="prawa">
            <? include("include/tables/table.ranking.php"); ?>
            <ul <?= (!empty($zalogowany) ? "class=\"one\"" : null) ?>>
                <?php

                $zapytanie = mysql_query("SELECT p._id_gracza, l.recordText, p.kolejnosc, p.id, l.recordID FROM " . BLOKI_MENU_PRZYPISANIE . " p LEFT JOIN   
					" . BLOKI_MENU_LISTA . " l ON l.recordID = p._id_bloku
					WHERE  p._id_gracza='" . DEFINIOWANE_ID . "' && p.vliga='" . DEFINIOWANA_GRA . "' ORDER BY p.kolejnosc ASC");
                while ($rek = mysql_fetch_array($zapytanie)) {
                    echo "<li  id=\"recordsArray_{$rek[3]}\">";
                    $ID_MODUL = $rek[4];
                    include("include/tables/table.{$rek[1]}.php");
                    echo "</li>";
                    $temp = TRUE;
                }
                ?>
            </ul><? include("include/tables/table.buttons.php"); ?>
            <? echo(empty($temp) ? note(m594, "blad") : "<div class=\"zaokraglenie_koniec\"><div class=\"zaokraglenie_lewa_dol\"></div><div class=\"zaokraglenie_prawa_dol\"></div></div> "); ?>
        </div>

        <div id="stopka">
            <div class="footer_1">Copyright (C) , <a href="mailto:stopek.pawel@gmail.com"
                                                     style="color:white; text-decoration:underline;">stopek</a>,
                www.pesarena.pl
            </div>
        </div>
    </div>
</div>
</body>
</html>

<!-- stat.4u.pl NiE KaSoWaC
 <a target=_top href="http://stat.4u.pl/?pesarenastopek"><img src="http://adstat.4u.pl/s4u.gif" border="0"></a>
 <script language="javascript" type="text/javascript">
 <!--
 function s4upl() { return "&amp;r=er";}
 //-->
</script>
<
script
language = "JavaScript"
type = "text/javascript"
src = "http://adstat.4u.pl/s.js?pesarenastopek" ></script>
<script language="JavaScript" type="text/javascript">
    <!--
    s4uext = s4upl();
    document.write('<img src="http://stat.4u.pl/cgi-bin/s.cgi?i=pesarenastopek' + s4uext + '" width=1 height1>')
    //-->
</script>
<noscript><img src="http://stat.4u.pl/cgi-bin/s.cgi?i=pesarenastopek&amp;r=ns" width="1" height="1"></noscript>
<!-- stat.4u.pl KoNiEc -->
