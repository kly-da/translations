<td>
	<input type="button" class="rate" mark="1" uid="<?=$user_id?>" 
		data-id="<?=$translation["translation_id"]?>" allow="<?=$allow?>" value="+">
	<span id="like_<?=$translation["translation_id"]?>">
		<?=$trans_like[$translation["translation_id"]]?></span>
</td>
<td>
	<input type="button" class="rate" mark="-1" uid="<?=$user_id?>" 
		data-id="<?=$translation["translation_id"]?>" allow="<?=$allow?>" value="-">
	<span id="dislike_<?=$translation["translation_id"]?>">
		<?=$trans_dislike[$translation["translation_id"]]?></span>
</td>
