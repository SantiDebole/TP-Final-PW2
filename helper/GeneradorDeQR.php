<<?php

require './vendor/phpqrcode/qrlib.php';
class GeneradorDeQR{

    public function __constructor(){}

    public function generarQRParaPerfil($usuario){
        $datoQR = "localhost/lobby/verrivalPorQR?usuario=$usuario";
        $archivo = "./public/QRs/$usuario.png";
        QRcode::png($datoQR, $archivo, QR_ECLEVEL_L, 10);
    }


}


