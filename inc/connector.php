<?php

$dbServername = "localhost";
$dbUsername = "luciens";
$dbPassword = "dalemace";

$dbName = "lampdemo";
$tableName = "books";

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
 		title varchar(255),
 		author varchar(255)
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
			$text .= "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['author'] . "</td></tr>";
		}
		$text .= "</table>";
		return $text;
	} else {
		return "<ul><li>No results found.</li></ul>";
	}
}

function addBook($conn, $tableName, $title, $author)
{
	$sql = "insert into " . $tableName . " ( title, author ) values ( '" . $title . "', '" . $author . "' )";
	return $conn->query($sql);
}

function deleteBook($conn, $tableName, $id)
{
	$sql = "delete from {$tableName} where id={$id}";
	return $conn->query($sql);
}

function updateBook($conn, $tableName, $id, $newTitle, $newAuthor){
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
