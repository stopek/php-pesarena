<span class="float-left">
	<a href="{$BASE_URL}addPlayer/{$id}/"><button>dodaj graczy</button></a>
	<a href="{$BASE_URL}deleteClan/{$id}/"><button>usu� klan</button></a>
</span>
<span class="float-left">
	<form method="get" action="{$BASE_URL}replecement/{$id}/">
		<select name="reType">
			<option value="1">zamiana na nowego gracza
			<option value="2">zamiana w obr�bie klanu
		</select>
		<input type="submit" value="zamie�"/>
	</form>
</span>