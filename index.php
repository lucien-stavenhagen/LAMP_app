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
    margin: auto;
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
    <form id="add-form" action="index.php" method="POST">
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title">
      </div>
      <div class="form-group">
        <label>Author</label>
        <input type="text" name="author">
      </div>
      <div class="form-group">
        <input type="submit" name="submitadd">
      </div>
      <br>
    </form>
    <form id="del-form" action="index.php" method="POST">
      <div class="form-group">
        <label>Delete book by id:</label>
        <input type="text" name="id">
      </div>
      <div class="form-group">
        <input type="submit" name="submitdel">
      </div>
      <br>
    </form>
  </section>

  <section class="table-display">
    <p><?php echo getAllBooks($conn, $tableName) ?></p>
  </section>
</body>
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>

</html>