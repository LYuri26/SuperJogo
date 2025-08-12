<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $dsn = 'mysql:host=localhost;dbname=superjogo;charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->pdo = new PDO($dsn, 'root', '', $options);
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }

    // Impede a clonagem do objeto
    private function __clone() {}

    // Impede a desserialização do objeto
    public function __wakeup()
    {
        throw new Exception("Não é possível desserializar uma instância de Database");
    }
}

// Função auxiliar para obter conexão
function getDBConnection()
{
    return Database::getInstance();
}
