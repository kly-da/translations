<?php
function clean($value = "") {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    
    return $value;
}
function check_length($value = "", $min, $max) {
    $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
    return !$result;
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$tetle = $_POST['tetle'];
	$message = $_POST['message'];
	$author = $_POST['author'];
	$tetle = clean($tetle);
	$message = clean($message);
	$author  = clean($author);
	if(!empty($tetle) && !empty($message) && !empty($author)) {
	    if(check_length($tetle, 2, 50)  && check_length($message, 10, 10000) && check_length($author, 1, 25)) {
			echo "Новость опубликована";
			echo $tetle."<br />".$message."<br />".$author."<br />";
	    } else {
	        echo "Введенные данные некорректные";
	    }
	} else {
	    echo "Заполните пустые поля";
	}
} else {
	header("Location: ../index.php");
}