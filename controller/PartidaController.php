<?php
class PartidaController {
    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function jugar() {
        // Trae la pregunta
        $pregunta = $this->model->traerPregunta();

        // Trae las respuestas asociadas a la pregunta
        $respuestas = $this->model->traerRespuestas($pregunta['id']);

        // Combina la pregunta con las respuestas en un solo array
        $preguntaConRespuestas = [
            'idPregunta' => $pregunta['id'], // Captura el ID de la pregunta
            'pregunta' => $pregunta['descripcion'], // Asegúrate de que sea la descripción de la pregunta
            'respuestas' => $respuestas
        ];

        // Muestra la vista con los datos
        $this->presenter->show('partida', $preguntaConRespuestas);
    }

    public function responder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener la respuesta seleccionada
            $idPregunta = $_POST['idPregunta'];
            $respuestaSeleccionada = $_POST['respuesta'];

            // Aquí puedes realizar la lógica para verificar si la respuesta es correcta
            // Puedes buscar la respuesta correcta en la base de datos o en el modelo

            // Por ejemplo, si tienes un método en tu modelo para verificar:
            $esCorrecta = $this->model->verificarRespuesta($idPregunta, $respuestaSeleccionada);

            // Puedes redirigir o mostrar un mensaje dependiendo del resultado
            if ($esCorrecta) {
                // Lógica si la respuesta es correcta
                $respuesta = [
                    'mensaje' => "Respuesta correcta."
                ];

                $this->presenter->show('respuesta', $respuesta);
            } else {
                // Lógica si la respuesta es incorrecta

                $respuesta = [
                    'mensaje' => "Respuesta incorrecta. Intenta de nuevo."
                ];

                $this->presenter->show('respuesta', $respuesta);
            }
        } else {
            // Si no es un POST, redirigir o mostrar un mensaje de error
            header("Location: /registro/listar");
            exit();
        }
    }


}
?>

