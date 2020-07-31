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
	$offset = 0;
	$pagesize = 3;
	$page = 1;
	$text = "<table id='results-table'><tr><th>Page</th><th>ID</th><th>Name</th><th>Street Address</th><th>City</th><th>State</th><th>Zipcode</th><th>Email</th><th>Phone</th></tr>";
	do {
		$text .= "<tr><td>".$page."</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		$pagesql = "select * from " . $tableName . " limit " . $offset . ", " . $pagesize;
		$result = $conn->query($pagesql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$text .= "<tr><td> </td><td>". $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['address'] . "</td><td>" . $row['city'] . "</td><td>" . $row['state'] . "</td><td>" . $row['zipcode'] . "</td><td>" . $row['email'] . "</td><td>" . $row['phone'] . "</td></tr>";
			}
		} else {
			$text .= "<ul><li>No results found.</li></ul>";
		}
		$offset += $pagesize;
		$page++;
	} while ($result->num_rows >= $offset);
	$text .= "</table>";

	return $text;
}

function addBook($conn, $tableName, $name, $address, $city, $state, $zipcode, $email, $phone)
{
	global $encThumbprint;

	$sql = "insert into " . $tableName . " ( name, address, city, state, zipcode, email, phone ) values ( '" . $name . "', '" . $address . "', '" . $city . "', '" . $state . "', '" . $zipcode . "', '" . $email . "', '" . $phone . "')";
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

if (array_key_exists('submitadd', $_REQUEST) and $_REQUEST['name'] and $_REQUEST['address']  and $_REQUEST['city'] and $_REQUEST['state'] and $_REQUEST['zipcode'] and $_REQUEST['email'] and $_REQUEST['phone']) {
	addBook($conn, $tableName, $_REQUEST['name'], $_REQUEST['address'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['zipcode'], $_REQUEST['email'], $_REQUEST['phone']);
}
if (array_key_exists('submitdel', $_REQUEST) and $_REQUEST['id']) {
	deleteBook($conn, $tableName, $_REQUEST['id']);
}
