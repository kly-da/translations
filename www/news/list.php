<?
  include('../mod_db.php');
  include('../mod_auth.php');

  $title = "News";
  include('../header.php');
?>

  <head>
	<link rel="stylesheet" tipe="text/css" href="css/style.css" />
  </head>
  <body>
  
	<div class="content" style="border: 0px;">
		
		<?
			$result = mysql_query("SELECT * FROM news") or die(mysql_error());
			$data = mysql_fetch_array($result);
			
			do {
				printf('<div class="article">
						<a href="#"><h3> %s </h3></a>
						<p>%s <a href="view.php?id=%s">Показать полностью</a></p>
						<p>%s</p>
						
				</div>',$data["title"], $data["news_text"], $data["news_id"], $data["date"]);
			}
			while($data = mysql_fetch_array($result));
			
		?>
		
	</div>
	
	<div class="user">
		<div class="middle_text">Пользователь</div>
	</div>
	<div style="clear:right;"/></div>
	<div class="news">
		<?
			include('news_rigth.php');
		?>
	</div>
  </body>

<? include('../footer.php');?> 