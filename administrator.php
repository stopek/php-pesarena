<? include("config.php");
include("include/admin/functions/admin.config.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= conf_adminTitleSite ?></title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2"/>
    <meta name="Description" content="Polski Serwis Pro Evolution Soccer 2009, 2008, 6, 5, 4, ISS"/>
    <meta name="Keywords" content="liga pes, pes league, leage, pro evoltin lig"/>
    <meta name="Author" content="Paweł Stopczyński"/>
    <meta name="Robots" content="index, follow"/>
    <meta name="Googlebot" content="index, follow"/>

    <script type="text/javascript" src="js/skrypty.js"></script>
    <script type="text/javascript" src="js/wysiwyg/ckeditor.js"></script>


    <?

    if ($_GET['a'] == 1) {
        if (mysql_query("DELETE FROM liga WHERE r_id = '29' ")) {
            note("ok", "info");
        }
    }

    ?>


    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.color.js"></script>

    <script type="text/javascript">
        var $a = jQuery.noConflict();
        $a(document).ready(function () {
            $a(".pane .jq").click(function () {
                var conf = confirm('Czy napewno chcesz to zrobic?');
                if (conf == true) {
                    $a("div.jq_alert").show();
                    var str = $a(this).attr("title");
                    var a = str.split("|");
                    var order = '&action=delete&id=' + a[1] + '&tb=' + a[0] + '&r=' + a[2];

                    $a.post("adminDB.php", order);
                    $a("div.jq_alert").html('<? note("wykonano", "info");?> ');
                    $a("div.jq_alert").animate({opacity: "hide"}, 4000);
                    $a(this).parents(".pane").animate({backgroundColor: "#00acb0"}, "fast")
                        .animate({opacity: "hide"}, "slow")

                    return false;
                }
            });
        });
    </script>
    <link rel="stylesheet" href="css/jquery.tabs.css" type="text/css" media="print, projection, screen"/>
    <!-- Additional IE/Win specific style sheet (Conditional Comments) -->
    <!--[if lte IE 7]>
    <link rel="stylesheet" href="css/jquery.tabs-ie.css" type="text/css" media="projection, screen"/>
    <![endif]-->

    <script src="js/tabs/jquery-1.1.3.1.pack.js" type="text/javascript"></script>
    <script src="js/tabs/jquery.history_remote.pack.js" type="text/javascript"></script>
    <script src="js/tabs/jquery.tabs.pack.js" type="text/javascript"></script>
    <script type="text/javascript">
        var $j = jQuery.noConflict();
        $j(function () {
            $j('.jquery_menu').tabs({fxSlide: true, fxFade: true, fxSpeed: 'normal'});
        });
    </script>
    <link type="text/css" rel="stylesheet" href="admin.css"/>

    <script src="js/tooltip/jquery.js" type="text/javascript"></script>
    <script src="js/tooltip/jquery.bgiframe.js" type="text/javascript"></script>
    <script src="js/tooltip/jquery.dimensions.js" type="text/javascript"></script>
    <script src="js/tooltip/jquery.tooltip.js" type="text/javascript"></script>
    <script src="js/tooltip/chili-1.7.pack.js" type="text/javascript"></script>
    <style> body {
            background-color: #2a292e;
        } </style>
    <script type="text/javascript">
        var $j = jQuery.noConflict();
        $j(function () {
            $j('#jquery_tooltip a, input.IH').tooltip({
                track: true,
                delay: 0,
                showURL: false,
                showBody: " - ",
                fade: 250
            });
        });


        var $u = jQuery.noConflict();
        $u(document).ready(function () {
            $u(".jquery_select_team a").click(function () {
                var varSelect = $u(".jquery_select_team select").attr("value");
                var varOld = $u(".jquery_select_team input[type=text]").attr("value");
                if (varOld == null) {
                    varOld = "";
                }

                if (varOld != null && varSelect != null) {
                    var parametrValue = varOld + "" + varSelect + ",";
                    $u(".jquery_select_team input[type=text]").attr({value: parametrValue});
                } else {
                    alert('Zaznacz jakas druzyne');
                }
            });
            $u(".jquery_select_team2 a").click(function () {
                var varSelect = $u(".jquery_select_team2 select").attr("value");
                var varOld = $u(".jquery_select_team2 input[type=text]").attr("value");
                if (varOld == null) {
                    varOld = "";
                }

                if (varOld != null && varSelect != null) {
                    var parametrValue = varOld + "" + varSelect + ",";
                    $u(".jquery_select_team2 input[type=text]").attr({value: parametrValue});
                } else {
                    alert('Zaznacz jakas druzyne');
                }
            });

            $u(".jquery_select_team2 a").addClass("cHand");
            $u(".jquery_select_team a").addClass("cHand");


            //$u(".error, .confirm").animate({ opacity: "hide" }, 10000)
        });


    </script>

</head>
<body>
<div id="adminControlPanel">
    <ul class="adminPage">
        <li class="adminTop">
            <div class="adminLogout">
                <span>Zalogowany jako: <? echo(!empty($szefuniu) ? $szefuniu . " (<a href=\"?opcja=wyloguj\">wyloguj</a>) " : "niezalogowany"); ?></span>
            </div>
            <ul id="jquery_tooltip">
                <?
                foreach ($tab as $value) {
                    echo "<li>
								<a href=\"administrator.php?wybor={$value}\" title=\"{$description[$value]}\" id=\"{$value}\"></a>
							</li>\n";
                }

                foreach ($ta as $key => $value) {
                    echo "<li>
								<a href=\"administrator.php?opcja={$value}\" title=\"{$description[$key]}\"  id=\"{$key}\"></a>
							</li>\n";
                }
                ?>
            </ul>
        </li>
        <li class="adminMenu">
            <div></div><?= $menu[$_SESSION['select']] ?></li>
        <li class="adminBody">

            <?

            if ($szefuniu) {
                include('include/admin/functions/admin.menu-manager.php');
            } else {
                require_once('include/admin/functions/admin.login.php');
                admin_login();
            }

            ?>
        </li>
        <li class="helpMenu">
            <?
            echo(conf_showDevil == 1 ? "<div id=\"devil\"></div>" : null);
            foreach ($wersje_gry as $value) {
                print "<div class=\"change_game_" . ($_COOKIE['save_game_league'] == $value ? "on" : "off") . "\">
							<a href=\"?save_game_league={$value}\">{$value}</a>
						</div>";
            }
            if (conf_ShowHelpMenu == 1) {
                ?>
                <div class="shortCutUL">
                    <ul>
                        <li><a href="administrator.php?opcja=9&pod=szuk&wybrana_gra=<?= $cookie_game ?>">Szukaj
                                gracza</a></li>
                        <li><a href="administrator.php?opcja=20&podmenu=add">napisz newsa</a></li>
                        <li><a href="administrator.php?opcja=17&wybrana_gra=<?= $cookie_game ?>">rozpocznij PD</a></li>
                        <li><a href="administrator.php?opcja=40&wybrana_gra=<?= $cookie_game ?>">wystaw pkt</a></li>
                        <li><a href="administrator.php?opcja=44"><img src="grafiki/admin_ikons/conf.png" alt=""/></a>
                        </li>
                        <li><a href="administrator.php?opcja=43"><img src="img/edit_profile_admin.png" alt=""/></a></li>
                        <li><a href="administrator.php?opcja=45" class="i-lang-conf"></a></li>
                    </ul>
                </div>
                <?
            }
            ?>
        </li>
        <li class="adminFooter"><span>www.pesarena.pl, Copyright (c) stopek, 2010, All rights reserved!</span></li>
    </ul>
</div>
</body>
</html>
