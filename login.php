<?php

  require "database.php";

  $error = null;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"]) || empty($_POST["password"])){
      $error = "Please fill all the fields.";
    } else if (!str_contains($_POST["email"], "@")) {
      $error = "Email format in incorrect";
    } else {
      // El "SELECT * FROM users WHERE email = :name" muestra solo la fila donde el email sea el que se especifica 
      $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
      $statement->bindparam(":email", $_POST["email"]);
      $statement->execute();

      // El rowCount devuelve el numero de filas afectadas por una sentencia, en este caso arriba se llamó a la fila del email ingresado,
      // en el caso de no existir, no devuelve ninguna fila por lo tanto es 0
      if ($statement->rowCount() == 0) {
        $error = "Invalid credentials.";
      } else {
        // en la siguiente acción: como la variable $statement contiene la fila de la tabla donde está el email que se especificó arriba,
        // $user toma los valores de la fila como un array indexado por nombre de colunmna
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        // El password_verify($password, $hash) comprueba que la contraseña coincida con un hash, devuelve true si coinciden, de lo contrario false
        if (!password_verify($_POST["password"], $user["password"])) {
          $error = "Invalid credentials.";
        } else {
          session_start();
          // unset() destruye la variable, en este caso destruimos la contraseña xq no hace falta y también como medida de seguridad por si hackean o algo pase
          unset($user["password"]);
          $_SESSION["user"] = $user;

          header("Location: home.php");
        }

        
      }
    }
  }
?>

<?php require "partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
          <?php if ($error): ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="login.php">

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" autocomplete="password" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require "partials/footer.php" ?>