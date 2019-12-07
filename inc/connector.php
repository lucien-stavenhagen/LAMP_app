<?php

$dbServername = "localhost";
$dbUsername = "luciens";
$dbPassword = "dalemace";

$dbName = "lampdemo";
$tableName = "books";

$encThumbprint = "abc-123+";

function encryptText($data, $thumbPrint){
	$enc_key=base64_decode($thumbPrint);
	$iv=openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	$encrypted=openssl_encrypt($data, 'aes-256-cbc',$enc_key, 0, $iv);
	return base64_encode($encrypted . "::" . $iv);
}

function decryptText($data, $thumbPrint){
	$enc_key=base64_decode($thumbPrint);
	list($encrypted,$iv)=array_pad(explode('::', base64_decode($data), 2),2,null);
	return openssl_decrypt($encrypted, 'aes-256-cbc', $enc_key, 0, $iv);
}

function connectToDb($dbServername, $dbUsername, $dbPassword, $dbName)
{
	$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
	if ($conn->connect_error) {
		return "<p> connection failed: " . $conn->connect_error . "</p>";
	}
	return $conn;
}

function createTable($conn, $tableName)
{
	$ok = true;
	$maintable = "create table if not exists " . $tableName . " (
		id int(6) unsigned auto_increment primary key,
 		title text,
 		author text
	)";
	return $conn->query($maintable);
}

function getAllBooks($conn, $tableName)
{
	$sql = "select * from " . $tableName;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$text = "<table id='results-table'><tr><th>ID</th><th>Title</th><th>Author</th></tr>";
		while ($row = $result->fetch_assoc()) {
			$text .= "<tr><td>" . $row['id'] . "</td><td>" . decryptText($row['title'],$encThumbprint) . "</td><td>" . decryptText($row['author'],$thumbPrint) . "</td></tr>";
		}
		$text .= "</table>";
		return $text;
	} else {
		return "<ul><li>No results found.</li></ul>";
	}
}

function addBook($conn, $tableName, $title, $author)
{
	$sql = "insert into " . $tableName . " ( title, author ) values ( '" .encryptText($title,$encThumbprint) . "', '" .encryptText($author,$encThumbprint) . "' )";
	return $conn->query($sql);
}

function deleteBook($conn, $tableName, $id)
{
	$sql = "delete from {$tableName} where id={$id}";
	return $conn->query($sql);
}

function updateBook($conn, $tableName, $id, $newTitle, $newAuthor){
	 $newTitle = encryptText($newTitle,$encThumbprint);
	 $newAuthor = encryptText($newAuthor,$encThumbprint);
	 
	$sql="update {$tableName} set title='{$newTitle}', author='{$newAuthor}' where id={$id}";
	return $conn->query($sql);
}
$conn = connectToDb($dbServername, $dbUsername, $dbPassword, $dbName);

createTable($conn, $tableName);

if ($_REQUEST['submitadd'] and $_REQUEST['title'] and $_REQUEST['author']) {
	addBook($conn, $tableName, $_REQUEST['title'], $_REQUEST['author']);
} elseif ($_REQUEST['submitdel'] and $_REQUEST['id']) {
	deleteBook($conn, $tableName, $_REQUEST['id']);
}elseif ($_REQUEST['submitedit'] and $_REQUEST['id'] and $_REQUEST['title']and $_REQUEST['author']){
	updateBook($conn, $tableName, $_REQUEST['id'], $_REQUEST['title'], $_REQUEST['author']);
}
