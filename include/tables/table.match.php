<!-- blok z ostanimi meczami -->
<div class="naglowek_prawa">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M227 ?>"/>
    <h1><?= M227 ?></h1>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>


<div class="jquery_menu">
    <ul class="yyy">
        <li><a href="#area-16"><span>Wyzwania</span></a></li>
        <li><a href="#area-17"><span>Liga</span></a></li>
        <li><a href="#area-18"><span>Puchar</span></a></li>
        <li><a href="#area-19"><span>Turniej</span></a></li>
    </ul>
    <div id="area-16" class="srodek_moje_mecze"><? $tabela = "wyzwania";
        include("include/last_match.php"); ?></div>
    <div id="area-17" class="srodek_moje_mecze"><? $tabela = "liga";
        include("include/last_match.php"); ?></div>
    <div id="area-18" class="srodek_moje_mecze"><? $tabela = 'puchar_dnia';
        include("include/last_match.php"); ?></div>
    <div id="area-19" class="srodek_moje_mecze"><? $tabela = "turniej";
        include("include/last_match.php"); ?></div>
</div>