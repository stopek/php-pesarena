<div id="centerBox" class="opacity90">
	<span class="white">Dodajesz gracza do klanu: {$clanName}
	<form method="post" action="">
		<select name="player_id">
			{foreach from=$x item=vars}
				<option value="{$vars.id}">{$vars.pseudo}</option>
			{/foreach}
		</select>
		<input type="submit" name="go" value="dodaj"/>
	</form>
</div>