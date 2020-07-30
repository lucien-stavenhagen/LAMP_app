<?php

$dbServername = "localhost";
$dbUsername = "luciens";
$dbPassword = "dalemace";

$dbName = "lampdemo";
$tableName = "addressbook";

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
 		name text,
		 address text,
		 email text,
		 phone text
	)";
	return $conn->query($maintable);
}

function getAllBooks($conn, $tableName)
{
	global $encThumbprint;
	$sql = "select * from " . $tableName;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$text = "<table id='results-table'><tr><th>ID</th><th>Name</th><th>Address</th><th>Email</th><th>Phone</th></tr>";
		while ($row = $result->fetch_assoc()) {
			$text .= "<tr><td>" . $row['id'] . "</td><td>" . decryptText($row['name'],$encThumbprint) . "</td><td>" . decryptText($row['address'],$encThumbprint) . "</td><td>". decryptText($row['email'],$encThumbprint) ."</td><td>". decryptText($row['phone'],$encThumbprint) . "</td></tr>";
		}
		$text .= "</table>";
		return $text;
	} else {
		return "<ul><li>No results found.</li></ul>";
	}
}

function addBook($conn, $tableName, $name, $address,$email,$phone)
{
	global $encThumbprint;
	
	$sql = "insert into " . $tableName . " ( name, address, email, phone ) values ( '" .encryptText($name,$encThumbprint) . "', '" .encryptText($address,$encThumbprint) . "', '" .encryptText($email,$encThumbprint) . "', '" .encryptText($phone,$encThumbprint) . "')";
	return $conn->query($sql);
}

function deleteBook($conn, $tableName, $id)
{
	$sql = "delete from {$tableName} where id={$id}";
	return $conn->query($sql);
}

function updateBook($conn, $tableName, $id, $newname, $newaddress, $newemail,$newphone){
	global $encThumbprint;
	 $newname = encryptText($newname,$encThumbprint);
	 $newaddress = encryptText($newaddress,$encThumbprint);
	 $newemail = encryptText($newemail,$encThumbprint);
	 $newphone = encryptText($newphone,$encThumbprint);
	 
	$sql="update {$tableName} set name='{$newname}', address='{$newaddress}', email='{$newemail}', phone='{$newphone}' where id={$id}";
	return $conn->query($sql);
}
$conn = connectToDb($dbServername, $dbUsername, $dbPassword, $dbName);

createTable($conn, $tableName);

if (array_key_exists('submitadd',$_REQUEST) and $_REQUEST['name'] and $_REQUEST['address'] and $_REQUEST['email'] and $_REQUEST['phone']) {
	addBook($conn, $tableName, $_REQUEST['name'], $_REQUEST['address'], $_REQUEST['email'],$_REQUEST['phone']);
} 
if (array_key_exists('submitdel',$_REQUEST) and $_REQUEST['id']) {
	deleteBook($conn, $tableName, $_REQUEST['id']);
}
if (array_key_exists('submitedit',$_REQUEST) and $_REQUEST['name']and $_REQUEST['address'] and $_REQUEST['email'] and $_REQUEST['phone']){
	updateBook($conn, $tableName, $_REQUEST['id'], $_REQUEST['name'], $_REQUEST['address'], $_REQUEST['email'], $_REQUEST['phone']);
}
