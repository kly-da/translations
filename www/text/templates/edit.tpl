<?if ($translation["user_id"] == $user_id && $can_translate) {?>
	<br>
	<input type="button" class="add_edit" value="Редактировать..." data-id="<?=$frag_num?>">
	</div>
	<div id="form_<?=$frag_num?>" style="display: none">
		<form>
			Перевод:<br>
			<textarea style="width: 100%" rows="10" 
				id="text_<?=$frag_num?>"><?=$translation["text"]?></textarea><br>
			<input type="hidden" id="len_<?=$frag_num?>" value="1">
			<input type="hidden" id="tid_<?=$frag_num?>" 
				value="<?=$translation["translation_id"]?>">
			<input type="hidden" id="fid_<?=$frag_num?>" 
				value="<?=$fragment["fragment_id"]?>">
			<input type="button" class="ok_edit" value="Ок" 
				data-id="<?=$frag_num?>">
			<input type="button" class="cancel" value="Отмена" data-id="<?=$frag_num?>">
		</form>
	</div>
<?}?>

