<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../styles/chapter_main.css">

<script>
	$(document).ready(function(){
		$('#pretext').on('click', '#save', function(event) {
			
			var $text = $("#trans").val();
			var $cid = $("#chapter_id").val();
			var $tid = $("#text_id").val();
			
			$.post("./db/full_save.php", {cid: $cid, text: $text}, function(ok) {
					window.location = "full_chapter.php?text_id=" + $tid + "&chapter_id=" + $cid;
				}
			);
			
		});
	});	
</script>

<?include('../header.php');?>

	<div id="pretext" class="edit_content" style="border: 0px;">
		<div>
			<div id="title_header"><?=$text_row["title"]?> — Глава <?=$chapter_row["chapter_number"]?>. 
				<?=$chapter_row["chapter_name"]?></div>
		</div>
		<div>
			<div width="100%"><?=$chapter_translation?></div>
			<form>
				<input type="hidden" id="trans" value="<?=$chapter_translation?>"/>		
				<input type="hidden" id="text_id" value="<?=$text_id?>"/>
				<input type="hidden" id="chapter_id" value="<?=$chapter_id?>"/>
				<input type="button" id="save" value="Сохранить перевод" <?if (!$full) {?>disabled<?}?> />
			</form>
		</div>
	</div>
	<div style="clear:right;"/></div>
  

<?include('../footer.php');?>
