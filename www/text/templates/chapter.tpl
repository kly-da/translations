<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script>
	$(document).ready(function(){
		$('#translate_table').on('click', '.add_edit', function(event) {
			var $num = $(this).attr('data-id');
			jQuery("#form_" + $num).toggle('show');
			$('#content_' + $num).toggle('show');
		});
	});
	
	$(document).ready(function(){
		$('#translate_table').on('click', '.cancel', function(event) {
			var $num = $(this).attr('data-id');
			jQuery("#content_" + $num).toggle('show');
			$("#form_" + $num).toggle('show');
		});
	});
</script>
<script>
	$(document).ready(function(){
		$('#translate_table').on('click', '.rate', function(event) {
			
			var $allow = $(this).attr('allow');
			if ($allow == 1) {
			
				var $num = $(this).attr('data-id');
				var $mark = $(this).attr('mark');
				var $uid = $(this).attr('uid');
				
				$.post("./db/rate.php", {tid: $num, uid: $uid, mark: $mark}, function(ok) {
						if (ok == 1) {
							if ($mark == "1")
							{
								var $likes = +($("#like_" + $num).text()) + 1;
								$("#like_" + $num).text($likes);
							}
							else if ($mark == "-1")
							{
								var $dislikes = +($("#dislike_" + $num).text()) + 1;
								$("#dislike_" + $num).text($dislikes);
							}
						}
					}
				);
			
			}
			
		});
	});
		
	$(document).ready(function(){
		$('#translate_table').on('click', '.ok_add', function(event) {
			
			var $num = $(this).attr('data-id');
			var $text = $("#text_" + $num).val();
			var $len = $("#len_" + $num).val();
			var $uid = $("#uid_" + $num).val();
			
			$.post("./db/insert_translation.php", {fid: $num, uid: $uid, len: $len, text: $text}, function(ok) {
					location.reload();
				}
			);
			
		});
	});	
	
	$(document).ready(function(){
		$('#translate_table').on('click', '.ok_edit', function(event) {
			
			var $num = $(this).attr('data-id');
			var $text = $("#text_" + $num).val();
			var $len = $("#len_" + $num).val();
			var $tid = $("#tid_" + $num).val();
			
			$.post("./db/edit_translation.php", {tid: $tid, len: $len, text: $text}, function(ok) {
					location.reload();
				}
			);
			
		});
	});
	
</script>

	<link rel="stylesheet" type="text/css" href="/styles/chapter_main.css">
<?
	include('../header.php');
?>

	<div class="edit_content" style="border: 0px;">
		<div>
			<div id="title_header"><?=$text_row["title"]?> — Глава <?=$chapter_row["chapter_number"]?>. 
				<?=$chapter_row["chapter_name"]?></div>
		</div>
		<div>
			<table id="translate_table" width=100% border="1" style='padding: 1px;'>
				
				<tr>
					<th>№</th>
					<th>Отрывок</th>
					<th colspan=3>Перевод</th>
				</tr>
				
				<?foreach ($fragments as $frag_num => $fragment) {?>
					<tr>
						<?$rowspan = $trans_count[$frag_num];
						if (!$translated[$frag_num])
							$rowspan++;?>
						<td rowspan="<?=$rowspan?>"><?=$frag_num?></td>
						<td rowspan="<?=$rowspan?>"><?=$fragment["text"]?></td>
						<?foreach ($translations[$frag_num] as $trans_num => $translation) {?>					
							<?if ($trans_num != 1) {?>
							<tr>
							<?}?>
								<td>
									<div id="content_<?=$frag_num?>"><?=$translation["text"]?>
								<?if ($translation["user_id"] == $user_id) {?>
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
											<input type="button" class="ok_edit" value="Ок" 
												data-id="<?=$fragment["fragment_id"]?>">
											<input type="button" class="cancel" value="Отмена" data-id="<?=$frag_num?>">
										</form>
									</div>
								<?} else {?>
									</div>
								<?}?>
								</td>
								<?$allow = 1; if ($translation["user_id"] == $user_id) $allow = 0;?>
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
							</tr>
						<?}?>
						
						
						<?if (!$translated[$frag_num]) {
							if ($trans_count[$frag_num] == 0) {?>
							<td colspan=3>
								<div id="content_<?=$frag_num?>">
									Пока переводов нет
						<?} else {?>
							<tr>
							<td colspan=3>
								<div id="content_<?=$frag_num?>">
						<?}?>
									<input type="button" class="add_edit" value="Добавить..." data-id="<?=$frag_num?>">
								</div>
								<div id="form_<?=$frag_num?>" style="display: none">
									<form>
										Перевод:<br>
										<textarea style="width: 100%" rows="10" id="text_<?=$frag_num?>"></textarea><br>
										<input type="hidden" id="len_<?=$frag_num?>" value="1">
										<input type="hidden" id="uid_<?=$frag_num?>" value="<?=$user_id?>">
										<input type="button" class="ok_add" value="Ок" data-id="<?=$fragment["fragment_id"]?>">
										<input type="button" class="cancel" value="Отмена" data-id="<?=$frag_num?>">
									</form>
								</div>
							</td>
						<?}?>
						</tr>
				<?}?>
				
			</table>
			
			<br>
			
		</div>
	</div>
	<div style="clear:right;"/></div>
  
<? include('../footer.php');?>
