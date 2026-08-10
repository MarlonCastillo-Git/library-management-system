<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca {
    private $db;
    private $conn;

    public function __construct() {
        // TODO: Inicializar conexión a base de datos
          
        $this->db = new Database();

        $this->conn = $this->db->getConnection();
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro) {
        // TODO: Insertar libro en base de datos

         $sql = "INSERT INTO libros (titulo, autor, isbn, cantidad)

                VALUES (:titulo, :autor, :isbn, :cantidad)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([

            ':titulo'   => $libro->getTitulo(),

            ':autor'    => $libro->getAutor(),

            ':isbn'     => $libro->getIsbn(),

            ':cantidad' => $libro->getCantidad()

        ]);

    
    }

    public function editarLibro($id, $nuevosDatos) {
        // TODO: Actualizar libro en base de datos

         $sql = "UPDATE libros

                SET titulo = :titulo,

                    autor = :autor,

                    isbn = :isbn,

                    cantidad = :cantidad

                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([

            ':titulo'   => $nuevosDatos['titulo'],

            ':autor'    => $nuevosDatos['autor'],

            ':isbn'     => $nuevosDatos['isbn'],

            ':cantidad' => $nuevosDatos['cantidad'],

            ':id'       => $id

        ]);

    
    }

    public function eliminarLibro($id) {
        // TODO: Eliminar libro de base de datos

         $sql = "DELETE FROM libros WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([

            ':id' => $id

        ]);
    }

    public function obtenerLibros() {
        // TODO: Retornar lista de libros disponibles

         $sql = "SELECT * FROM libros WHERE cantidad > 0";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function buscarLibro($id) {
        // TODO: Retornar un libro específico

         $sql = "SELECT * FROM libros WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([

            ':id' => $id

        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario) {
        // TODO: Insertar usuario en base de datos

        $sql = "INSERT INTO usuarios (nombre, email, telefono)

                VALUES (:nombre, :email, :telefono)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([

            ':nombre'   => $usuario->getNombre(),

            ':email'    => $usuario->getEmail(),

            ':telefono' => $usuario->getTelefono()

        ]);


    }

    public function editarUsuario($id, $nuevosDatos) {
        // TODO: Actualizar usuario en base de datos

$sql = "UPDATE usuarios

                SET nombre = :nombre,

                    email = :email,

                    telefono = :telefono

                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([

            ':nombre'   => $nuevosDatos['nombre'],

            ':email'    => $nuevosDatos['email'],

            ':telefono' => $nuevosDatos['telefono'],

            ':id'       => $id

        ]);

    }

    public function eliminarUsuario($id) {
        // TODO: Eliminar usuario de base de datos

         $sql = "DELETE FROM usuarios WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([

            ':id' => $id

        ]);
    }

    public function obtenerUsuarios() {
        // TODO: Retornar lista de usuarios

        $sql = "SELECT * FROM usuarios";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

       // return [];
    }

    // Gestión de Préstamos

    public function prestarLibro($libro_id, $usuario_id) {
        // TODO: Crear registro de préstamo y actualizar stock de libros

        try {

            // Iniciar transacción

            $this->conn->beginTransaction();

            // Verificar existencia y stock del libro

            $sql = "SELECT cantidad

                    FROM libros

                    WHERE id = :libro_id

                    FOR UPDATE";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':libro_id' => $libro_id

            ]);

            $libro = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$libro) {

                throw new Exception("El libro no existe.");

            }

            if ($libro['cantidad'] <= 0) {

                throw new Exception("No hay ejemplares disponibles.");

            }

            // Verificar que el usuario exista

            $sql = "SELECT id

                    FROM usuarios

                    WHERE id = :usuario_id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':usuario_id' => $usuario_id

            ]);

            if (!$stmt->fetch()) {

                throw new Exception("El usuario no existe.");

            }

            // Fecha actual

            $fecha_prestamo = date('Y-m-d');

            // Crear préstamo

            $sql = "INSERT INTO prestamos

                    (libro_id, usuario_id, fecha_prestamo, estado)

                    VALUES

                    (:libro_id, :usuario_id, :fecha_prestamo, 'activo')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':libro_id'       => $libro_id,

                ':usuario_id'     => $usuario_id,

                ':fecha_prestamo' => $fecha_prestamo

            ]);

            // Reducir cantidad disponible

            $sql = "UPDATE libros

                    SET cantidad = cantidad - 1

                    WHERE id = :libro_id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':libro_id' => $libro_id

            ]);

            // Confirmar cambios

            $this->conn->commit();

            return true;

        } catch (Exception $e) {

            // Deshacer cambios si ocurre un error

            if ($this->conn->inTransaction()) {

                $this->conn->rollBack();

            }

            throw $e;

        }

    }




    public function devolverLibro($prestamo_id) {
        // TODO: Actualizar fecha de devolución y estado del préstamo, actualizar stock
    
        try {

            $this->conn->beginTransaction();

            // Buscar préstamo activo

            $sql = "SELECT libro_id

                    FROM prestamos

                    WHERE id = :prestamo_id

                    AND estado = 'activo'

                    FOR UPDATE";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':prestamo_id' => $prestamo_id

            ]);

            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$prestamo) {

                throw new Exception(

                    "El préstamo no existe o ya fue devuelto."

                );

            }

            $libro_id = $prestamo['libro_id'];

            // Fecha actual de devolución

            $fecha_devolucion = date('Y-m-d');

            // Actualizar préstamo

            $sql = "UPDATE prestamos

                    SET fecha_devolucion = :fecha_devolucion,

                        estado = 'devuelto'

                    WHERE id = :prestamo_id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':fecha_devolucion' => $fecha_devolucion,

                ':prestamo_id'      => $prestamo_id

            ]);

            // Regresar ejemplar al inventario

            $sql = "UPDATE libros

                    SET cantidad = cantidad + 1

                    WHERE id = :libro_id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                ':libro_id' => $libro_id

            ]);

            $this->conn->commit();

            return true;

        } catch (Exception $e) {

            if ($this->conn->inTransaction()) {

                $this->conn->rollBack();

            }

            throw $e;

        }

    }
    


    public function obtenerPrestamosActivos() {
        // TODO: Retornar lista de préstamos activos

          $sql = "SELECT

                    p.id,

                    p.libro_id,

                    l.titulo,

                    p.usuario_id,

                    u.nombre AS usuario,

                    p.fecha_prestamo,

                    p.estado

                FROM prestamos p

                INNER JOIN libros l

                    ON p.libro_id = l.id

                INNER JOIN usuarios u

                    ON p.usuario_id = u.id

                WHERE p.estado = 'activo'

                ORDER BY p.fecha_prestamo DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // return [];
    }
}
