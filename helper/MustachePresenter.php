<?php

class MustachePresenter{
    private $mustache;
    private $partialsPathLoader;

    public function __construct($partialsPathLoader){
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
            'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsPathLoader )
        ));
        $this->partialsPathLoader = $partialsPathLoader;
    }

    public function show($contentFile, $data=array()){
        echo $this->generateHtml($this->partialsPathLoader.'/'.$contentFile.'View.mustache', $data);
    }

    public function generateHtml($contentFile, $data=array()){
        $contentAsString = file_get_contents($this->partialsPathLoader.'/header.mustache');
        $contentAsString .= file_get_contents($contentFile);
        $contentAsString .= file_get_contents($this->partialsPathLoader.'/footer.mustache');
        /*El operador .= permite construir una cadena compleja agregando contenido de manera secuencial.
        En este caso, el código construye una página HTML completa al unir una cabecera, el contenido principal y un pie de página,
        para luego procesarla y renderizarla usando Mustache.* */
        return $this->mustache->render($contentAsString, $data);
    }

}