<?if ($trans_count[$frag_num] == 0 || ($can_translate && !$translated[$frag_num])) {?>
<?if ($trans_count[$frag_num] != 0) {?>
<tr>
<?}?>
	<td class="add<? if (!$trans_count[$frag_num] == 0) {?> addmid<?}?>">
	<div id="content_<?=$frag_num?>">
		<? if ($trans_count[$frag_num] == 0) {?>
			<span class="notrans">Переводов пока нет</span>
		<?}?>
		<? if ($can_translate) {?>
		<input type="button" class="add_edit" value="Добавить..." data-id="<?=$frag_num?>">
	</div>
	<div id="form_<?=$frag_num?>" style="display: none">
		<form>
			Перевод:<br>
			<textarea style="width: 100%" rows="10" id="text_<?=$frag_num?>"></textarea><br>
			<input type="hidden" id="len_<?=$frag_num?>" value="1">
			<input type="hidden" id="uid_<?=$frag_num?>" value="<?=$user_id?>">
			<input type="hidden" id="fid_<?=$frag_num?>" value="<?=$fragment["fragment_id"]?>">
			<input type="button" class="ok_add" value="Ок" data-id="<?=$frag_num?>">
			<input type="button" class="cancel" value="Отмена" data-id="<?=$frag_num?>">
		</form>
	</div>
		<?} else {?>
	</div>
		<?}?>
	</td>
<?}?>
</tr>
