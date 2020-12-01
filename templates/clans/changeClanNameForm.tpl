<span class="float-left">
	{if $check}
		<form method="post" action="">
			<input type="hidden" name="clan_id" value="{$id}"/>
			<input type="text" name="name" value="{$clanName}"/>
			<input type="submit" name="goName" value="zapisz"/>
		</form>			
	{else}
		<a href="{$BASE_URL}changeClanName/{$id}/"><button>{$clanName}</button></a>
	{/if}
</span>