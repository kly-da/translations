	<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../styles/chapter_main.css">
<?include('../header.php');?>

	<div class="edit_content" id="text" style="border: 0px;">
		<div>
			<div id="title_header"><?=$text_row["title"]?> — Глава <?=$chapter_row["chapter_number"]?>. 
				<?=$chapter_row["chapter_name"]?></div>
		</div>
		<div>
			<?=$chapter_translation?>
		</div>
		<table class="chapters" width="100%">
			<tr>
				<td><a href="#">Предыдущая глава</a></td><td><a href="#">Следующая глава</a></td>
			</tr>
		<table>
	</div>
	<div style="clear:right;"/></div>
  

<?include('../footer.php');?>
