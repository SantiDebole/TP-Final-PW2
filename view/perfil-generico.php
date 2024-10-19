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
    <a href="../view/lobby-generico.php" class="back">Volver</a>
    <div class="foto_y_nombre">
        <img src="../public/image/juan_perez.jpg" alt="Foto de perfil" class="foto-perfil">
        <h1 class="nombre-usuario">Juan</h1>


    </div>

    <form id="perfilForm">
        <div class="informacion">
            <label for="fotoInput">Cambiar Foto de Perfil:</label>
            <input type="file" id="fotoInput" accept="image/*" disabled>

            <label for="nombreCompleto">Nombre Completo:</label>
            <input type="text" id="nombreCompleto" value="Juan Pérez" disabled>

            <label for="username">Username:</label>
            <input type="text" id="username" value="@juaan90" disabled>

            <label for="anoNacimiento">Año de Nacimiento:</label>
            <input type="number" id="anoNacimiento" value="1990" disabled>

            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" value="San justo" disabled>

            <label for="pais">País:</label>
            <input type="text" id="pais" value="Argentina" disabled>

           <iframe  id="mapa" class="mapa" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13125.456632530742!2d-58.5628052!3d-34.6707576!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc62cc3ef7083%3A0x8867107f425fade5!2sUniversidad%20Nacional%20de%20La%20Matanza!5e0!3m2!1ses-419!2sar!4v1729111178631!5m2!1ses-419!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <label for="genero">Género:</label>
            <select id="genero" disabled>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Prefiero no decirlo</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" id="email" value="juan.perez@mail.com" disabled>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" value="12345Juan*" disabled>

            <button type="button" id="editarBtn">Editar</button>
            <button type="submit" id="guardarBtn" disabled>Guardar</button>
        </div>
    </form>
</div>

<script src="../scripts/script.js"></script>
</body>
</html>
