<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Perfil de Usuario</title>
</head>
<body>
<div class="perfil">
    <a href="#" class="back">Volver</a>
    <div class="foto_y_nombre">
        <img src="../public/image/<?php echo $_SESSION['usuario']['foto']; ?>" alt="Foto de perfil" class="foto-perfil">
        <h1 class="nombre-usuario"><?php echo $_SESSION['usuario']['nombre']; ?></h1>
    </div>

    <form id="perfilForm" method="POST" action="PerfilController.php?action=editarPerfil">
        <div class="informacion">
            <label for="fotoInput">Cambiar Foto de Perfil:</label>
            <input type="file" id="fotoInput" accept="image/*">

            <label for="nombreCompleto">Nombre Completo:</label>
            <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo $_SESSION['usuario']['nombre']; ?>">

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $_SESSION['usuario']['username']; ?>" disabled>

            <label for="anoNacimiento">Año de Nacimiento:</label>
            <input type="number" name="anoNacimiento" id="anoNacimiento" value="<?php echo $_SESSION['usuario']['anoNacimiento']; ?>">

            <label for="ciudad">Ciudad:</label>
            <input type="text" name="ciudad" id="ciudad" value="<?php echo $_SESSION['usuario']['ciudad']; ?>">

            <label for="pais">País:</label>
            <input type="text" name="pais" id="pais" value="<?php echo $_SESSION['usuario']['pais']; ?>">

            <label for="genero">Género:</label>
            <select name="genero" id="genero">
                <option value="masculino" <?php if ($_SESSION['usuario']['genero'] == 'masculino') echo 'selected'; ?>>Masculino</option>
                <option value="femenino" <?php if ($_SESSION['usuario']['genero'] == 'femenino') echo 'selected'; ?>>Femenino</option>
                <option value="otro" <?php if ($_SESSION['usuario']['genero'] == 'otro') echo 'selected'; ?>>Prefiero no decirlo</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $_SESSION['usuario']['email']; ?>">

            <button type="submit" id="guardarBtn">Guardar</button>
        </div>
    </form>
</div>

<script src="../scripts/script.js"></script>
</body>
</html>