<?php
include_once 'inc/connector.php'
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <style type="text/css" media="screen">
  </style>
</head>
<style>
  #results-table,
  #results-table th,
  #results-table td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
  }

  .form-section,
  .table-display {
    width: 80%;
    margin:2px auto;
    padding: 15px;
    border-radius: 4px;
    border: 1px solid rgba(0,0,0, .3);
    overflow: auto;
  }

  .form-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .form-group label,
  .form-group input {
    padding: 5px;
    margin: 2px;
  }
</style>

<body>
  <section class="form-section">
    <h2 class="form-title">Enter New Address</h2>
    <form id="add-form" action="index.php" method="POST">
      <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name">
      </div>
      <div class="form-group">
        <label>Street Address:</label>
        <input type="text" name="address">
      </div>
      <div class="form-group">
        <label>City:</label>
        <input type="text" name="city">
      </div>
      <div class="form-group">
        <label>State:</label>
        <input type="text" name="state">
      </div>
      <div class="form-group">
        <label>Zipcode:</label>
        <input type="text" name="zipcode">
      </div>
      <div class="form-group">
        <label>Email:</label>
        <input type="text" name="email">
      </div>
      <div class="form-group">
        <label>Phone:</label>
        <input type="text" name="phone">
      </div>
      <div class="form-group">
        <input type="submit" name="submitadd" value="Submit">
      </div>
      <br>
    </form>
    <h2 class="form-title">Remove An Address</h2>
    <form id="del-form" action="index.php" method="POST">
      <div class="form-group">
        <label>Delete book by id:</label>
        <input type="text" name="id">
      </div>
      <div class="form-group">
        <input type="submit" name="submitdel" value="Delete">
      </div>
      <br>
    </form>
    <!--
    <h2 class="form-title">Edit An Address</h2>
    <form id="edit-form" action="index.php" method="POST">
      <div class="form-group">
      	<label>ID of book to edit:</label>
	<input type="text" name="id">
      </div>
      <div class="form-group">
        <label>New Address:</label>
        <input type="text" name="name">
      </div>
      <div class="form-group">
        <label>New Author:</label>
        <input type="text" name="address">
      </div>
      <div class="form-group">
        <label>New Email:</label>
        <input type="text" name="email">
      </div>
      <div class="form-group">
        <label>New Phone:</label>
        <input type="text" name="phone">
      </div>
      <div class="form-group">
        <input type="submit" name="submitedit" value="Edit Book">
      </div>
      <br>
    </form>
    -->
</section>

  <section class="table-display">
    <h2 class="form-title">Addresses In Database</h2>
    <p><?php echo getAllBooks($conn, $tableName) ?></p>
  </section>
</body>
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>

</html>