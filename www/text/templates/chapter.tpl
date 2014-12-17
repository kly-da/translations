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
			var $fid = $("#fid_" + $num).val();
			var $cid = $("#cid_" + $num).val();
			
			
			$.post("./db/insert_translation.php", {cid: $cid, fid: $fid, uid: $uid, len: $len, text: $text}, function(ok) {
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
			var $fid = $("#fid_" + $num).val();
			
			
			$.post("./db/edit_translation.php", {tid: $tid, len: $len, text: $text}, function(ok) {
					location.reload();
				}
			);
			
		});
	});
	
	$(document).ready(function(){
		$('#translate_table').on('click', '.ban', function(event) {
			
			var $num = $(this).attr('data-id');
			var $cid = $(this).attr('cid');
			var $status = $(this).attr('status');
			
			$.post("./db/ban.php", {tid: $num, cid: $cid, status: $status}, function(ok) {
					location.reload();
				}
			);
			
		});
	});	
	
	$(document).ready(function(){
		$('#translate_table').on('click', '.best', function(event) {
			
			var $num = $(this).attr('data-id');
			var $pid = $(this).attr('pid');
			
			$.post("./db/best.php", {tid: $num, pid: $pid}, function(ok) {
					location.reload();
				}
			);
			
		});
	});	
	
</script>

	<link rel="stylesheet" type="text/css" href="../styles/chapter_main.css">
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
					<th>Перевод</th>
				</tr>
				
				<?foreach ($fragments as $frag_num => $fragment) {?>
					<tr>
						<?$rowspan = $trans_count[$frag_num] * 2;
						if (!$translated[$frag_num] && $can_translate)
							$rowspan++;?>
						<td class="num" rowspan="<?=$rowspan?>"><?=$frag_num?></td>
						<td rowspan="<?=$rowspan?>"><?=$fragment["text"]?></td>
						<?foreach ($translations[$frag_num] as $trans_num => $translation) {
							$content = $translation["text"];?>					
							<?if ($trans_num != 1) {?>
							<tr>
							<?}?>
							<td class="trans<?if ($trans_num == 1) {?> first<?}?><?if ($translation["best"]) {?> best<?}?><?if ($translation["banned"]) {?> banned<?}?>">
							<div id="content_<?=$frag_num?>"><?=$content?>
							<?include('templates/edit.tpl')?>
							</div></td>
							<?$allow = 1; if ($translation["user_id"] == $user_id) $allow = 0;?>
							</tr>
							<tr style="border-top: 0;">
								<td class="<?if ($trans_num == $trans_count[$frag_num] && !$allow) {?>lastopt<?} else {?>opt<?}?>">
									<span class="username"><a href="../user/view.php?id=<?=$translation["user_id"]?>"><?=$translation["name"]?></a></span>
									<?include('templates/rate.tpl')?>
									<?include('templates/moderate.tpl')?>
								</td>
							</tr>
						<?}?>

						<?include('templates/add.tpl');?>	
						
				<?}?>
				
			</table>
			
			<br>
			
		</div>
		<table class="chapters" width="100%">
			<tr>
				<td><a href="#">Предыдущая глава</a></td><td><a href="#">Следующая глава</a></td>
			</tr>
		<table>
	</div>
	<div style="clear:right;"/></div>
  
<? include('../footer.php');?>
