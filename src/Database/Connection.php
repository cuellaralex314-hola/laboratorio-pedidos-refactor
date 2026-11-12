<?php


class Connection
{
    private static ?Connection $instance = null;
    /** Almacen en memoria para poder correr la demo sin MySQL levantado. */
    private static array $tablaEnMemoria = [];

    private function __construct()
    {
        // Constructor privado para evitar instanciación directa
    }
    public static function getInstance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function ejecutar(string $sql):void
    {
        try {
            self::$tablaEnMemoria[] = $sql;
        } catch (Throwable $e) {
            throw new RuntimeException("Error al ejecutar la consulta: " , 0, $e);
        }
    }
    public function consultas(): array
    {
        return self::$tablaEnMemoria;
    }
}