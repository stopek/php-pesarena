<div id="centerBox2">	<span>wykonujesz zamian� dru�yny dla klnau: <strong>{$clanName}</strong></span>	<form method="post" action="">		<select name="team_id">			{foreach from=$teams item=vars}				<option value="{$vars.id}">{$vars.nazwa}</option>			{/foreach}		</select>		<input type="submit" name="goClanTeam" value="zmie� dru�yn�"/>	</form></div>