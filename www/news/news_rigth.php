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