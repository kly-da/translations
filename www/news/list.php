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
			$result = mysql_query("SELECT * FROM news ORDER BY news_id DESC LIMIT 5") or die(mysql_error());
			if (mysql_num_rows($result)>0){  
				while ($data = mysql_fetch_assoc($result)){  
					printf('<div class="news_title">
						<a href="view.php?id=%s"><p> %s </p></a>
						<p>%s</p>
					</div>',$data["news_id"], $data["title"], $data["date"]);
				}}
			
		?>
	</div>
  </body>

<? include('../footer.php');?> 