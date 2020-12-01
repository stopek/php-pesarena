<form method="post" action="">
	<input type="hidden" name="id" value="{$id}"/>
	<select name="type">
		<option value="0" {if not $status} selected {/if}>mo¿liwo¶æ zmian - wy³±czona
		<option value="1" {if $status} selected {/if}>mo¿liwo¶æ zmian - w³±czona
	</select>
	<input type="submit" name="goStatus" value="zmieñ"/>
</form>