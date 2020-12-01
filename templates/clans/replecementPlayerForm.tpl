<span class="float-left">
	<a href="{$BASE_URL}addPlayer/{$id}/"><button>dodaj graczy</button></a>
	<a href="{$BASE_URL}deleteClan/{$id}/"><button>usuñ klan</button></a>
</span>
<span class="float-left">
	<form method="get" action="{$BASE_URL}replecement/{$id}/">
		<select name="reType">
			<option value="1">zamiana na nowego gracza
			<option value="2">zamiana w obrêbie klanu
		</select>
		<input type="submit" value="zamieñ"/>
	</form>
</span>