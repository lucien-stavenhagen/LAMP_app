<?php

$dbServername = "localhost";
$dbUsername = "luciens";
$dbPassword = "dalemace";

$dbName = "lampdemo";
$tableName = "addressbook";

/*
$encThumbprint = "abc-123+";

function encryptText($data){
	global $encThumbprint;
	$enc_key=base64_decode($encthumbPrint);
	$iv=openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	$encrypted=openssl_encrypt($data, 'aes-256-cbc',$enc_key, 0, $iv);
	return base64_encode($encrypted . "::" . $iv);
}

function decryptText($data){
	global $encThumbprint;
	$enc_key=base64_decode($encThumbPrint);
	list($encrypted,$iv)=array_pad(explode('::', base64_decode($data), 2),2,null);
	return openssl_decrypt($encrypted, 'aes-256-cbc', $enc_key, 0, $iv);
}
*/
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
 		name text,
		 address text,
		 city text,
		 state text,
		 zipcode text,
		 email text,
		 phone text
	)";
	return $conn->query($maintable);
}

function getAllBooks($conn, $tableName)
{
	global $encThumbprint;
	$offset = 1;
	$pagesize = 1;

	$sql = "select * from " . $tableName;
	$pagesql = $sql . " limit " . $offset . ", " . $pagesize;
#	$result = $conn->query($pagesql);
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$text = "<table id='results-table'><tr><th>ID</th><th>Name</th><th>Street Address</th><th>City</th><th>State</th><th>Zipcode</th><th>Email</th><th>Phone</th></tr>";
		while ($row = $result->fetch_assoc()) {
			$text .= "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['address'] . "</td><td>" . $row['city'] ."</td><td>". $row['state'] . "</td><td>".$row['zipcode']. "</td><td>". $row['email'] ."</td><td>". $row['phone'] . "</td></tr>";
		}
		$result->free();
		$text .= "</table>";
		return $text;
	} else {
		return "<ul><li>No results found.</li></ul>";
	}
}

function addBook($conn, $tableName, $name, $address,$city, $state, $zipcode, $email,$phone)
{
	global $encThumbprint;
	
	$sql = "insert into " . $tableName . " ( name, address, city, state, zipcode, email, phone ) values ( '" .$name . "', '" .$address. "', '" .$city. "', '" .$state. "', '" .$zipcode. "', '" .$email. "', '" .$phone. "')";
	return $conn->query($sql);
}

function deleteBook($conn, $tableName, $id)
{
	$sql = "delete from {$tableName} where id={$id}";
	return $conn->query($sql);
}

/*function updateBook($conn, $tableName, $id, $newname, $newaddress, $newemail,$newphone){
	 
	$sql="update {$tableName} set name='{$newname}', address='{$newaddress}', email='{$newemail}', phone='{$newphone}' where id={$id}";
	return $conn->query($sql);
}
*/
$conn = connectToDb($dbServername, $dbUsername, $dbPassword, $dbName);

createTable($conn, $tableName);

if (array_key_exists('submitadd',$_REQUEST) and $_REQUEST['name'] and $_REQUEST['address']  and $_REQUEST['city'] and $_REQUEST['state'] and $_REQUEST['zipcode'] and $_REQUEST['email'] and $_REQUEST['phone']) {
	addBook($conn, $tableName, $_REQUEST['name'], $_REQUEST['address'], $_REQUEST['city'], $_REQUEST['state'],$_REQUEST['zipcode'],$_REQUEST['email'],$_REQUEST['phone']);
} 
if (array_key_exists('submitdel',$_REQUEST) and $_REQUEST['id']) {
	deleteBook($conn, $tableName, $_REQUEST['id']);
}

