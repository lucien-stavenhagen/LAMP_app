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
    width: 40%;
    margin:2px auto;
    padding: 15px;
    border-radius: 4px;
    border: 1px solid rgba(0,0,0, .3);
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
    <h2 class="form-title">Enter New Book</h2>
    <form id="add-form" action="index.php" method="POST">
      <div class="form-group">
        <label>Title:</label>
        <input type="text" name="title">
      </div>
      <div class="form-group">
        <label>Author:</label>
        <input type="text" name="author">
      </div>
      <div class="form-group">
        <input type="submit" name="submitadd" value="Submit New Book">
      </div>
      <br>
    </form>
    <h2 class="form-title">Remove A Book</h2>
    <form id="del-form" action="index.php" method="POST">
      <div class="form-group">
        <label>Delete book by id:</label>
        <input type="text" name="id">
      </div>
      <div class="form-group">
        <input type="submit" name="submitdel" value="Delete Book">
      </div>
      <br>
    </form>
    <h2 class="form-title">Edit A Book</h2>
    <form id="edit-form" action="index.php" method="POST">
      <div class="form-group">
      	<label>ID of book to edit:</label>
	<input type="text" name="id">
      </div>
      <div class="form-group">
        <label>New Title:</label>
        <input type="text" name="title">
      </div>
      <div class="form-group">
        <label>New Author:</label>
        <input type="text" name="author">
      </div>
      <div class="form-group">
        <input type="submit" name="submitedit" value="Edit Book">
      </div>
      <br>
    </form>
</section>

  <section class="table-display">
    <h2 class="form-title">Database Library</h2>
    <p><?php echo getAllBooks($conn, $tableName) ?></p>
  </section>
</body>
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>

</html>