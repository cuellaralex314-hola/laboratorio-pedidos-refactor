<?php

class Connection
{
    private static ?Connection $instance= null;
    private static array $tablaEnMemoria = [];

    
    private function __construct() {}
     
      public static function getInstance(): Connection
      {
          if (self::$instance === null) {
              self::$instance = new Connection();
          }
          return self::$instance;
      }

    public function ejecutar(string $sql): void
    {
        try {
            self::$tablaEnMemoria[] = $sql;
        } catch (Throwable $e) {
            throw new RuntimeException('Error al ejecutar la consulta', 0, $e);
        }
    }

    public function consultas(): array
    {
        return self::$tablaEnMemoria;
    }
}
