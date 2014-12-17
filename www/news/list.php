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
					<?
						include("content.php");
					?>
				</div>',$data["title"], $data["news_text"]);
			}
			while($data = mysql_fetch_array($result));
			
		?>
		
	</div>
	
	<div class="user">
		<div class="middle_text">Пользователь</div>
	</div>
	<div style="clear:right;"/></div>
	<div class="news">
    <div class="middle_text">Новости</div>
	</div>
  </body>

<? include('../footer.php');?> 