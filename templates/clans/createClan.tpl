<div id="centerBox" class="opacity90">	<form method="post" action="">		<input type="text" name="name"/>		<select name="team_id">			{foreach from=$x item=vars}				<option value="{$vars.id}">{$vars.nazwa}</option>			{/foreach}		</select>		<input type="submit" name="go" value="dodaj klan"/>	</form></div>