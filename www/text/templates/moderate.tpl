<?if ($moderate) {?>
<span class="moderate">
	<?if (!$translation["banned"]) {?>
		<input type="image" src="../logo/ban.png" class="ban" status="ban"
			cid="<?=$chapter_id?>" data-id="<?=$translation["translation_id"]?>" value="#">
	<?} else {?>
		<input type="image" src="../logo/unban.png" class="ban" status="unban" 
			cid="<?=$chapter_id?>" data-id="<?=$translation["translation_id"]?>" value="~">
	<?}?>
</span>
<span class="moderate">
	<input type="image" src="../logo/star.png" class="best" 
		data-id="<?=$translation["translation_id"]?>" pid="<?=$best_trans[$frag_num]?>" value="^_^">
</span>
<?}?>
