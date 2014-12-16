<?if ($user->isTextModerator()) {?>
<td>
	<?if (!$translation["banned"]) {?>
		<input type="button" class="ban" status="ban"
			data-id="<?=$translation["translation_id"]?>" value="#">
	<?} else {?>
		<input type="button" class="ban" status="unban" 
			data-id="<?=$translation["translation_id"]?>" value="~">
	<?}?>
</td>
<td>
	<input type="button" class="best" 
		data-id="<?=$translation["translation_id"]?>" pid="<?=$best_trans[$frag_num]?>" value="^_^">
</td>
<?}?>
