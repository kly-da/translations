<<<<<<< HEAD
<?
=======
﻿<?
>>>>>>> 851bfc8e1dba7291e36748c6e92f79536780a3e8
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
<<<<<<< HEAD
						<a href="#"><h3> %s </h3></a>
						<p>%s <a href="view.php?id=%s">Показать полностью</a></p>
						<p>%s</p>
						
				</div>',$data["title"], $data["news_text"], $data["news_id"], $data["date"]);
=======
					<?
						include("content.php");
					?>
				</div>',$data["title"], $data["news_text"]);
>>>>>>> 851bfc8e1dba7291e36748c6e92f79536780a3e8
			}
			while($data = mysql_fetch_array($result));
			
		?>
		
	</div>
	
	<div class="user">
		<div class="middle_text">Пользователь</div>
	</div>
	<div style="clear:right;"/></div>
	<div class="news">
<<<<<<< HEAD
		<?
			include('news_rigth.php');
		?>
=======
    <div class="middle_text">Новости</div>
>>>>>>> 851bfc8e1dba7291e36748c6e92f79536780a3e8
	</div>
  </body>

<? include('../footer.php');?> 