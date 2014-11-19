<!DOCTYPE html>
<html>	
	<head>
		<title>Ввод новости</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<form action="app/write_new.php" method="post">
			<p>Заголовок новости: <input name="tetle" type="text"></p>
			<p>Текст новости: <br /><textarea name="message" cols="30" rows="5"></textarea></p>
			<p>Автор: <br /><input name="author" type="text"></p>
			<p><input type='submit' value='Отпубликовать'></p>
		</form>
	</body>
</html>