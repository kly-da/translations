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
			if(!isset($_GET['id'])){
				$id=1;
			}else
			{
				$id=$_GET['id'];
			}
			$result = mysql_query("SELECT * FROM news WHERE news_id='$id'") or die(mysql_error());
			$data = mysql_fetch_array($result);
			
			do {
				printf('<div class="article">
						<h2> %s </h2>
						<p>%s</p>
						<p>%s</p>
						<a href="administrating.php?id=%s">Редактировать</a>
						
				</div>',$data["title"], $data["news_text"], $data["date"], $data["news_id"]);
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