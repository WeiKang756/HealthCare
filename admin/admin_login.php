<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <style media="screen">

.icon{
  height: 80px;
  width: 80px;
  margin-left: 160px;
  margin-top: 56px;
}
  </style>
  <body>
    <nav class="navbar">
      <div class="container-fluid">
        <a class="navbar-brand logo" href="#"><img src="../Images/logo.png" alt="logo" class="img-fluid"></a>

      </div>
    </nav>

<main class="container py-5">
  <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-lg-6">
      <h1>Admin Log In</h1>
      <form class="" action="admin_login_action.php" method="post">

        <label for="admin_username">Admin Username</label>
        <input type="text" name="admin_username" value="" class="form-control">

        <label for="admin_password">Password</label>
        <input type="password" name="admin_password" value="" class="form-control">

        <button type="submit" class="btn btn-primary w-100">Sign In</button>

      </form>
    </div>

    <div class="col-lg-6">

    </div>

  </div>
<main>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
