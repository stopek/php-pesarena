<?
function block_1($title, $left, $right)
{
    ?>


    <div class="blok_wersja_gry_title"><?= $title ?></div>
    <div class="blok_wersja_gry">
        <div class="blok_wersja_gry_lewa">
            <?= $left ?>
        </div>
        <div class="blok_wersja_gry_prawa">
            <ul><? foreach ($right as $key => $value) {
                    echo "<li>{$key} : {$value}</li>";
                } ?></ul>
        </div>
    </div>
    <div class="blok_wersja_gry_dol"></div>
    <hr class="blok_wersja_hr"/>

    <?

}

?>