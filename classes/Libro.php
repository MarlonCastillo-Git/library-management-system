<?php

class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $isbn;
    private $cantidad;

    public function __construct($titulo = null, $autor = null, $isbn = null, $cantidad = 1) {
        // TODO: Inicializar atributos
          
        $this->titulo = $titulo;

        $this->autor = $autor;

        $this->isbn = $isbn;

        $this->cantidad = $cantidad;
    }

    // Getters y Setters
    public function getId() {
        // TODO
        return $this->id;
    }

    public function getTitulo() {
        // TODO
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        // TODO
        $this->titulo = $titulo;
    }

    public function getAutor() {
        // TODO
        return $this->autor;
    }

    public function setAutor($autor) {
        // TODO
        $this->autor = $autor;
    }

    public function getIsbn() {
        // TODO
        return $this->isbn;
    }

    public function setIsbn($isbn) {
        // TODO
        $this->isbn = $isbn;
    }

    public function getCantidad() {
        // TODO
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        // TODO
        $this->cantidad = $cantidad;
    }
}
