{if $status}
	<span class="float-right">
		<a href="{$BASE_URL}unready/{$id}/"><button class="green-button">cofnij gotowo¶æ</button></a>
	</span>
{else}
	<span class="float-right">
		<a href="{$BASE_URL}ready/{$id}/"><button class="important-button">zg³o¶ gotowo¶æ</button></a>
	</span>
{/if}