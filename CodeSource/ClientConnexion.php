<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
        <title>Page de connexion</title>
    </head>
    <body class="login">
    <form class="connexionform" action="./ClientWithJWT" method="post">
        <h1 class="connexion">Connexion</h1>
      <label for="username">Nom d'utilisateur:</label>
      <input type="text" id="username" name="username"><br><br>
      <label for="password">Mot de passe:</label>
      <input type="password" id="password" name="password"><br><br>
      <input type="submit" value="Se connecter">
    </form>
  </body>
</html>