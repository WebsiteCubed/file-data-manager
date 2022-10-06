<?php
  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/src/utilities.php';

  $file = 'users.json'; // use either users.json or users.xml for the file name.
  $data = new App\JSONDataManager($file); // Pass JSON or XML users file to either JSONDataManager or XMLDataManager.
  $api = new App\Api($data);
  $user = $api->single(1);
  $all = $api->all();

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    if ($_POST['username'] && $_POST['email'])
    {
      $attributes['username'] = sanitizeInput($_POST['username']);
      $attributes['email'] = sanitizeInput($_POST['email']);

      $api->create($attributes);

      $all = $api->all();

      header("Location: {$_SERVER['PHP_SELF']}?saved");
    } elseif (isset($_POST['delete'])){
      $attributes['id'] = sanitizeInput($_POST['delete']);

      $api->delete($attributes['id']);

      $all = $api->all();
      header("Location: {$_SERVER['PHP_SELF']}?deleted");
    } else {
      echo 'Please make sure fields are not empty.';
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Data Manager</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; display:flex; justify-content:center;">
  <div style="width: 400px;">
  <h2>File Data Manager</h2>
  <p>Save or delete data to <span style="background: #555; color:#fff; padding:3px;"><?= $file ?></span> file.</p>
  <?php
    if (isset($_GET['saved'])){
      echo '<span style="color:#008000; font-weight: bold;">User added! </span>' . '<a href=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '>Back</a>';
    } elseif (isset($_GET['deleted'])){
      echo '<span style="color:#008000; font-weight: bold;">User deleted! </span>' . '<a href=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '>Back</a>';
    }
  ?>
    <div style="padding: 1em; border: solid 2px #ccc; margin-bottom: 1em">
      <h3>Create:</h3>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <div style="padding-bottom: 5px;">
          <label for="username" style="display:block;">Username</label>
          <input type="text" name="username">
        </div>
        <div style="padding-bottom: 5px;">
          <label for="email" style="display:block;">Email</label>
          <input type="email" name="email">
        </div>
        <div style="padding-bottom: 5px;">
          <input type="submit" value="Save">
        </div>
      </form>
    </div>
    <div style="padding: 1em; border: solid 2px #ccc; margin-bottom: 1em">
      <h3>Return Single:</h3> 
      <?php 
        echo 'id: ' . $user['id'] . '<br>';
        echo 'username: ' . $user['username'] . '<br>';
        echo 'email: ' . $user['email'] . '<br>';
      ?>
    </div>
    <div style="padding: 1em; border: solid 2px #ccc; margin-bottom: 1em">
      <h3>Return All:</h3>
      <?php foreach($all as $user) : ?>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              id: <?= $user['id'] ?><br>
              username: <?= $user['username'] ?><br>
              email: <?= $user['email'] ?><br>
            </div>
            <div>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <input type="hidden" name="delete" value="<?= $user['id'] ?>">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this entry?');">
              </form>
            </div>
          </div>
          <br>
      <?php endforeach ?>
    </div>
  </div>
</body>
</html>