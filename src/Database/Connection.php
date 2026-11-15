<<?php

final class Connection
{
    private static ?self $instance = null;
    private ?PDO $pdo = null;

    private function __construct(private array $config)
    {
    }

    public static function getInstance(array $config = []): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /** Alias en español para conservar una llamada familiar. */
    public static function obtener(array $config = []): self
    {
        return self::getInstance($config);
    }

    public function pdo(): PDO
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        if (($this->config['driver'] ?? 'mysql') !== 'mysql') {
            throw new RuntimeException('El único controlador de base de datos configurado es MySQL.');
        }

        $host = trim((string) ($this->config['host'] ?? ''));
        $name = trim((string) ($this->config['name'] ?? ''));
        $user = trim((string) ($this->config['user'] ?? ''));
        $pass = (string) ($this->config['pass'] ?? '');
        $port = (int) ($this->config['port'] ?? 3306);
        $charset = (string) ($this->config['charset'] ?? 'utf8mb4');

        if ($host === '' || $name === '' || $user === '') {
            throw new RuntimeException(
                'Falta configurar MySQL. Copiá config/database.example.php a config/database.php y completá host, name y user.'
            );
        }

        if (!in_array('mysql', PDO::getAvailableDrivers(), true)) {
            throw new RuntimeException(
                'El PHP que ejecuta la aplicación no tiene habilitado pdo_mysql. Habilitá la extensión en el php.ini de XAMPP y reiniciá Apache.'
            );
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $host,
            $port,
            $name,
            $charset
        );

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'No se pudo conectar a MySQL. Verificá que MySQL esté iniciado en XAMPP y que config/database.php tenga los datos correctos.',
                0,
                $exception
            );
        }

        return $this->pdo;
    }
}
