<?require_once('klany-config.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
	<head>
		<script type="text/javascript" src="http://demos111.mootools.net/demos/mootools.svn.js"></script>
		<script type="text/javascript" src="<?=FURL?>js/tooltip.js"></script>
		
		<link type="text/css" rel="stylesheet" href="<?=FURL?>clans.css" />
		<title>Panel Zarzadzania Klanami</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2"/>
		<link rel="shortcut icon" type="image/ico" href="<?=FURL?>favicon.ico" />
		
		
		
		<!-- uploader -->
		<link href="http://stopczynski.pl/pesarena/js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="http://stopczynski.pl/pesarena/js/uploadify/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="http://stopczynski.pl/pesarena/js/uploadify/swfobject.js"></script>
		<script type="text/javascript" src="http://stopczynski.pl/pesarena/js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
			
	</head>
<body>
	<div id="header">
		<ul>
			<li><img src="<?=FURL?>img/logofk.jpg" style="margin: 12px 4px 0 3px;" alt=""/></li>
			<li><a href="<?=BASE_URL?>"><button>menu główne</button></a></li>
			<li><a href="<?=BASE_URL?>startRecords/"><button>zapisy</button></a></li>
			<li><a href="<?=BASE_URL?>createClan/"><button>utwórz klan</button></a></li>
			<li><a href="<?=BASE_URL?>match/"><button>kolejki</button></a></li>
			<li><a href="<?=BASE_URL?>table/"><button>tabela</button></a></li>
			<li><a href="<?=BASE_URL?>ranking/"><button>ranking</button></a></li>
			<li><a href="<?=BASE_URL?>timetable/"><button>terminarz</button></a></li>
			<li><a href="<?=BASE_URL?>results/"><button class="important-button">wyniki</button></a></li>
			<li><a href="<?=BASE_URL?>logout/"><button class="green-button">wyloguj się</button></a></li>
			<!--<li><span class="float-right padding-15 top-text">Zalogowany jako: <?=$_SESSION['klan_name_user']?></span></li>-->
		</ul>
	</div>
	<div id="content">
		<?if (!empty($_SESSION['klany_access'])) require_once('include/clans/'.$loadConfigFile[ACCESS]); else lForm($smarty); ?>
	</div>
	
</body>
</html>