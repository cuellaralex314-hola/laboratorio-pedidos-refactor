# DOCUMENTACIÓN DE PRUEBAS DEL PROYECTO

## 1. Introducción

El presente documento describe las pruebas automatizadas realizadas sobre el proyecto de refactorización `mini-proyecto-laboratorio`.

El objetivo es verificar el correcto funcionamiento de los componentes desarrollados, comprobar la implementación de los patrones de diseño y detectar posibles errores ante entradas o situaciones no válidas.

Se utilizan dos mecanismos de ejecución:

- **PHPUnit:** framework utilizado para ejecutar las pruebas unitarias automatizadas.
- **Runner personalizado:** archivo `tests/run.php`, que ejecuta comprobaciones adicionales sobre los componentes principales.

## 2. Entorno de pruebas

| Elemento | Configuración |
|---|---|
| Lenguaje | PHP |
| Versión utilizada localmente | PHP 8.5.11 |
| Framework de pruebas | PHPUnit 13.4.7 |
| Base de datos de pruebas | SQLite en memoria, cuando está disponible |
| Ejecutor adicional | `tests/run.php` |
| Configuración PHPUnit | `phpunit.xml` |

## 3. Casos de prueba

### 3.1. Strategy y cálculo de precios

**Archivo:** `PricingStrategyTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Calcular el precio para los tipos de paciente admitidos | Positivo | Se aplica correctamente el cálculo correspondiente. |
| Utilizar un tipo de paciente desconocido | Negativo | Se rechaza el tipo no admitido. |
| Enviar un importe inválido | Negativo | Se rechaza el importe. |
| Enviar un importe infinito | Negativo | Se rechaza el valor no válido. |
| Utilizar descuentos fuera del rango permitido | Negativo | Se rechazan los descuentos incorrectos. |
| Consultar los tipos admitidos | Positivo | Se devuelve la lista de tipos soportados. |

### 3.2. Factory y creación de notificaciones

**Archivo:** `FactoryTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Crear una notificación por correo electrónico | Positivo | Se obtiene un objeto de notificación válido. |
| Crear una notificación SMS | Positivo | Se obtiene un objeto de notificación válido. |
| Crear una notificación WhatsApp | Positivo | Se obtiene un objeto de notificación válido. |
| Solicitar un tipo de notificación desconocido | Negativo | Se rechaza la solicitud. |
| Enviar un destino vacío | Negativo | Se rechaza el destino incorrecto. |

### 3.3. Adapter y compatibilidad con código heredado

**Archivo:** `AdapterTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Comprobar que el adaptador implementa el contrato Notification | Positivo | El adaptador cumple la interfaz requerida. |
| Enviar una notificación mediante send() | Positivo | El adaptador traduce la llamada a sendMessage(). |
| Comprobar la respuesta del componente heredado | Positivo | Se devuelve el resultado generado por LegacyNotifier. |

### 3.4. Decorator y generación de reportes

**Archivo:** `DecoratorTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Aplicar una firma digital al reporte | Positivo | Se incorpora la firma al contenido. |
| Convertir el reporte a PDF | Positivo | Se incorpora el formato PDF. |
| Agregar una marca de agua | Positivo | Se incorpora la marca de agua. |
| Encadenar diferentes decoradores | Positivo | Se combinan correctamente las funcionalidades. |
| Generar un reporte diario decorado | Positivo | Se obtiene el reporte con los decoradores configurados. |

### 3.5. Observer y notificación de eventos

**Archivo:** `ObserverTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Registrar observadores | Positivo | Los observadores quedan suscritos. |
| Emitir un evento | Positivo | Los observadores registrados reciben la notificación. |
| Comprobar el orden de notificación | Positivo | Se respeta el orden esperado. |
| Cancelar una suscripción | Positivo | El observador deja de recibir notificaciones. |

### 3.6. Facade y procesamiento de pedidos

**Archivo:** `FacadeSrpTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Procesar un pedido válido | Positivo | Se valida, calcula, persiste, notifica y emite el evento correspondiente. |
| Procesar un pedido con tipo de paciente desconocido | Negativo | Se rechaza antes de guardar el pedido. |
| Verificar la coordinación de dependencias | Positivo | El servicio coordina correctamente los componentes involucrados. |

### 3.7. Modelo Order

**Archivo:** `OrderTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Crear un pedido con datos válidos | Positivo | Se obtiene un pedido válido. |
| Utilizar un paciente vacío | Negativo | Se rechaza la información. |
| Utilizar un importe no positivo | Negativo | Se rechaza el importe. |

### 3.8. Repository y persistencia

**Archivo:** `RepositoryStrategyTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Guardar un pedido | Positivo | El pedido queda almacenado. |
| Recuperar los pedidos | Positivo | Se devuelven los registros existentes. |
| Actualizar un pedido existente | Positivo | Se modifican los datos correspondientes. |
| Actualizar un pedido inexistente | Negativo | Se informa el error esperado. |

Esta prueba utiliza SQLite en memoria y requiere que la extensión `pdo_sqlite` esté habilitada. Si no está disponible, PHPUnit puede marcarla como omitida.

### 3.9. Singleton y conexión

**Archivo:** `SingletonTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Obtener la instancia mediante getInstance() | Positivo | Se obtiene la instancia de conexión. |
| Obtener la instancia mediante obtener() | Positivo | Se obtiene la misma instancia. |
| Comparar ambas instancias | Positivo | Las dos referencias corresponden al mismo objeto. |

### 3.10. Controladores y vistas MVC

**Archivos:** `MvcTest.php` y `MvcSrpTest.php`

| Caso | Tipo | Resultado esperado |
|---|---|---|
| Ejecutar el método index() | Positivo | Se muestra la vista correspondiente. |
| Mostrar datos de usuario | Positivo | Se representan correctamente los datos. |
| Introducir contenido que requiere escape HTML | Negativo / seguridad | El contenido se escapa para evitar su interpretación como HTML ejecutable. |
| Generar un reporte desde el controlador | Positivo | Se obtiene la salida esperada del reporte. |

## 4. Pruebas personalizadas adicionales

**Archivo:** `tests/run.php`

Además de PHPUnit, el proyecto dispone de un ejecutor personalizado que comprueba nueve comportamientos:

1. Strategy: cálculo de los cuatro tipos de paciente.
2. PriceCalculator: rechazo de tipos de paciente desconocidos.
3. Factory: creación de notificaciones WhatsApp.
4. Adapter: adaptación de send() a sendMessage().
5. Decorator: composición de firma, PDF y marca de agua.
6. Observer: notificación a los observadores registrados.
7. Facade: validación, cálculo, persistencia, notificación y eventos.
8. Order: rechazo de pacientes vacíos e importes no positivos.
9. ReportGenerator: composición del reporte diario.

## 5. Resultados de ejecución

En la ejecución realizada localmente se obtuvieron los siguientes resultados:

| Ejecutor | Pruebas | Aserciones | Fallos |
|---|---:|---:|---:|
| PHPUnit | 37 | 62 | 0 |
| Runner personalizado | 9 | No contabilizadas como aserciones PHPUnit | 0 |

**Resultado:** ambas ejecuciones finalizaron correctamente.

Nota: el progreso de PHPUnit mostró un símbolo de prueba omitida. Por lo tanto, debe verificarse si `RepositoryStrategyTest.php` fue ejecutada o marcada como omitida antes de afirmar que todos los casos se ejecutaron.

## 6. Conclusión

Las pruebas automatizadas permiten verificar el comportamiento de los componentes refactorizados, comprobar los patrones de diseño implementados y detectar errores ante situaciones no válidas.

La combinación de PHPUnit y el ejecutor personalizado proporciona una cobertura de pruebas sobre los principales componentes del proyecto. La documentación de casos positivos y negativos permite dejar constancia de los escenarios verificados y facilita futuras tareas de mantenimiento y mejora del software.