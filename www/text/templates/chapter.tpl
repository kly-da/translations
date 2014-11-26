<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script>
	$(document).ready(function(){
		$('#translate_table').on('click', '.add', function(event) {
			var $num = $(this).attr('data-id');
			jQuery("#form_" + $num).toggle('show');
			$('#add_content_' + $num).toggle('show');
		});
	});
	
	$(document).ready(function(){
		$('#translate_table').on('click', '.cancel', function(event) {
			var $num = $(this).attr('data-id');
			jQuery("#add_content_" + $num).toggle('show');
			$("#form_" + $num).toggle('show');
		});
	});
</script>
<script>
	$(document).ready(function(){
		$('#translate_table').on('click', '.rate', function(event) {
			var $num = $(this).attr('data-id');
			var $mark = $(this).attr('mark');
			
			$.post("./db/rate.php", {trans_id: $num, user_id: "1", mark: $mark}, function(ok) {
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
			);
			
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
						<td rowspan="<?=$trans_count[$frag_num] + 1?>"><?=$frag_num?></td>
						<td rowspan="<?=$trans_count[$frag_num] + 1?>"><?=$fragment["text"]?></td>
						
						<?foreach ($translations[$frag_num] as $trans_num => $translation) {?>					
							<?if ($trans_num != 1) {?>
							<tr>
							<?}?>
								<td><?=$translation["text"]?></td>
								<td>
									<input type="button" class="rate" mark="1" 
										data-id="<?=$translation["translation_id"]?>" value="+">
									<span id="like_<?=$translation["translation_id"]?>">
										<?=$trans_like[$translation["translation_id"]]?></span>
								</td>
								<td>
									<input type="button" class="rate" mark="-1" 
										data-id="<?=$translation["translation_id"]?>" value="-">
									<span id="dislike_<?=$translation["translation_id"]?>">
										<?=$trans_dislike[$translation["translation_id"]]?></span>
								</td>
							</tr>
						<?}?>
						
						
							
						<?if ($trans_count[$frag_num] == 0) {?>
							<td colspan=3>
								<div id="add_content_<?=$frag_num?>">
									Пока переводов нет
						<?} else {?>
							<tr>
							<td colspan=3>
								<div id="add_content_<?=$frag_num?>">
						<?}?>
									<input type="button" class="add" value="Добавить..." data-id="<?=$frag_num?>">
								</div>
								<div id="form_<?=$frag_num?>" style="display: none">
									<form>
										Перевод:<br>
										<textarea style="width: 100%" rows="10" id="text_<?=$frag_num?>"></textarea><br>
										<input type="hidden" id="len_<?=$frag_num?>" value="1">
										<input type="hidden" id="uid_<?=$frag_num?>" value="1">
										<input type="button" class="ok_add" value="Ок" data-id="<?=$fragment["fragment_id"]?>">
										<input type="button" class="cancel" value="Отмена" data-id="<?=$frag_num?>">
									</form>
								</div>
							</td>
						</tr>
				<?}?>
				
			</table>
			
			<br>
			
		</div>
	</div>
	<div style="clear:right;"/></div>
  
<? include('../footer.php');?>
