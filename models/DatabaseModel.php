<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {

        $host = 'localhost';
        $dbname = 'tienda';
        $user = 'root';
        $pass = '';
        //$charset = $dotenv['DB_CHARSET'];

        $dsn = "mysql:host=$host;dbname=$dbname;";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devuelve arrays asociativos
                PDO::ATTR_EMULATE_PREPARES => false, // Previene inyecciones SQL
                PDO::ATTR_PERSISTENT => false // Evita conexiones persistentes innecesarias
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Método Singleton para obtener la conexión
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>