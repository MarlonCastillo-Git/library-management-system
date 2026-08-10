<?php

class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $telefono;

    public function __construct($nombre = null, $email = null, $telefono = null) {
        // TODO: Inicializar atributos

         $this->nombre = $nombre;

        $this->email = $email;

        $this->telefono = $telefono;
    }

    // Getters y Setters
    public function getId() {
        // TODO
        return $this->id;
    }

    public function getNombre() {
        // TODO
        return $this->nombre;
    }

    public function setNombre($nombre) {
        // TODO
        $this->nombre = $nombre;
    }

    public function getEmail() {
        // TODO
    return $this->email;
    }

    public function setEmail($email) {
        // TODO
        $this->email = $email;
    }

    public function getTelefono() {
        // TODO
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        // TODO
        $this->telefono = $telefono;
    }
}
