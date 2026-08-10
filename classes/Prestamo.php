<?php

class Prestamo {
    private $id;
    private $libro_id;
    private $usuario_id;
    private $fecha_prestamo;
    private $fecha_devolucion;
    private $estado;

    public function __construct($libro_id = null, $usuario_id = null) {
        // TODO: Inicializar atributos, establecer fecha_prestamo a hoy
    $this->libro_id = $libro_id;
    $this->usuario_id = $usuario_id;
    $this->fecha_prestamo = date('Y-m-d');

    }

    // Getters y Setters
    public function getId() {
        // TODO
        return $this->id;
    }

    public function getLibroId() {
        // TODO
        return $this->libro_id;
    }

    public function getUsuarioId() {
        // TODO
        return $this->usuario_id;
    }

    public function getFechaPrestamo() {
        // TODO
        return $this->fecha_prestamo;
    }

    public function getFechaDevolucion() {
        // TODO
        return $this->fecha_devolucion;
    }

    public function setFechaDevolucion($fecha) {
        // TODO
        $this->fecha_devolucion = $fecha;
    }

    public function getEstado() {
        // TODO
        return $this->estado;
    }

    public function setEstado($estado) {
        // TODO
        $this->estado = $estado;
    }
}
