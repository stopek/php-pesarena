<div class="naglowek_prawa">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M232 ?>"/>
    <h1><?= M232 ?></h1>
    <div class="_info"><a href="#"><?= na_stronie() ?>/<?= odwiedzin() ?></a></div>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>

<div class="jquery_menu">
    <ul class="yyy">
        <li><a href="#area-10"><span>Uzytkownicy</span></a></li>
        <li><a href="#area-11"><span>Goscie</span></a></li>
    </ul>
    <div id="area-10" class="srodek_online"><? online(strip_tags($host), 'user'); ?></div>
    <div id="area-11" class="srodek_online"><? online(strip_tags($host), 'gosc'); ?></div>
</div>
