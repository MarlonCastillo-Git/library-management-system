<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'biblioteca';
    private $username = 'root';
    private $password = 'kabanchik7701*';
    public $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;
        
        // TODO: Implementar la conexión a la base de datos utilizando PDO
     try {
        $this->conn = new PDO(
            "mysql:host=" . $this->host .   
            ";dbname=" . $this->db_name .
            ";charset=utf8mb4",
        $this->username,
        $this->password
        );

$this->conn->setAttribute(
PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION
);

} catch (PDOException $e) {
echo "Error de conexión: " . $e->getMessage();
}  
        

        return $this->conn;
    }
}
