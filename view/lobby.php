<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia Master</title>
    <link rel="stylesheet" href="../styles/stylesLobby.css">
</head>
<body>
<div class="container">

    <img src="../public/image/logo.png" class="logo">

    <?php
    include '../controllers/UserController.php';
    $userController = new LobbyController($conn);

    $user = $userController->showUser(1);
    ?>

    <div class="nombre_y_puntaje">
        <a href="perfil-generico.php"><h1 id="user-name"><?php echo htmlspecialchars($user['nombre']); ?></h1></a>
        <p>Puntaje: <?php echo htmlspecialchars($user['puntaje']); ?></p>
    </div>

    <div class="buttons">
        <button class="btn">Crear partida</button>
        <button class="btn">Ver ranking</button>
        <button class="btn">Ver partidas anteriores</button>
        <button class="btn">Crear nuevas preguntas</button>
    </div>
</div>
</body>
</html>
