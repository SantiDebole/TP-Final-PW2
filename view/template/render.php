
<html lang="es"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"></html>
<?php



require 'C:/xampp/htdocs/TP-Final-PW2/vendor/autoload.php'; // Asegúrate de que la ruta es correcta

// Crea el motor de plantillas Mustache
$mustache = new Mustache_Engine;

// Carga la plantilla
$template = file_get_contents('./registroFinalizadoView.mustache');



// Renderiza la plantilla
$output = $mustache->render($template);

echo $output;
