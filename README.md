# Laboratorio de Pedidos – Refactorización y Patrones de Diseño

## 1. Descripción del proyecto

Este proyecto consiste en la refactorización y mejora de un sistema de gestión de pedidos, aplicando buenas prácticas de programación, principios de diseño de software y patrones de diseño.

El objetivo principal es mejorar la organización, mantenibilidad, reutilización y extensibilidad del código, separando las responsabilidades de los componentes y facilitando la incorporación de nuevas funcionalidades.

En la rama `feat/ie3-testing` se incorporaron mejoras relacionadas con la implementación e integración de patrones de diseño, validaciones, seguridad, persistencia de datos, generación de reportes, notificaciones y pruebas automatizadas.

También se incorporó un proceso de integración continua mediante GitHub Actions para automatizar la validación de sintaxis y la ejecución de las pruebas.

## 2. Objetivos

- Aplicar patrones de diseño para resolver problemas habituales de desarrollo.
- Mejorar la separación de responsabilidades.
- Reducir el acoplamiento entre los componentes.
- Facilitar el mantenimiento y la ampliación del sistema.
- Implementar validaciones para los datos de los pedidos.
- Incorporar mecanismos de seguridad para los formularios.
- Organizar el acceso a la base de datos mediante repositorios.
- Implementar pruebas automatizadas.
- Automatizar las verificaciones del proyecto mediante GitHub Actions.

## 3. Tecnologías utilizadas

| Tecnología | Utilización |
|---|---|
| PHP 8.5 | Lenguaje de programación y ejecución local de PHPUnit |
| MySQL | Sistema de gestión de base de datos |
| PDO | Conexión y operaciones con la base de datos |
| PHPUnit 13.4.7 | Pruebas automatizadas |
| HTML y PHP | Presentación e interacción con el sistema |
| Git | Control de versiones |
| GitHub | Repositorio remoto y gestión del código |
| GitHub Actions | Integración continua |

## 4. Arquitectura y organización del proyecto

El proyecto utiliza una organización por responsabilidades, separando controladores, modelos, servicios, repositorios, notificaciones, reportes y otros componentes.

### Estructura de directorios

```text
mini-proyecto-laboratorio/
│
├── .github/
│   └── workflows/
│       └── php.yml
│
├── config/
│   └── database.example.php
│
├── database/
│   └── schema.sql
│
├── docs/
│   └── PRUEBAS.md
│
├── public/
│   └── index.php
│
├── src/
│   ├── Bootstrap/
│   │   └── ApplicationFactory.php
│   │
│   ├── Controllers/
│   │   └── OrderController.php
│   │
│   ├── Database/
│   │   └── Connection.php
│   │
│   ├── Events/
│   │   ├── DashboardObserver.php
│   │   ├── EmailObserver.php
│   │   ├── OrderEvents.php
│   │   ├── OrderObserver.php
│   │   ├── OrderSubject.php
│   │   └── SmsObserver.php
│   │
│   ├── Legacy/
│   │   ├── LegacyNotifier.php
│   │   └── LegacyNotifierAdapter.php
│   │
│   ├── Models/
│   │   └── Order.php
│   │
│   ├── Notifications/
│   │   ├── EmailNotification.php
│   │   ├── Notification.php
│   │   ├── NotificationFactory.php
│   │   ├── NotificationSender.php
│   │   ├── SmsNotification.php
│   │   └── WhatsAppNotification.php
│   │
│   ├── Pricing/
│   │   ├── DiscountPricingStrategy.php
│   │   ├── JubiladoStrategy.php
│   │   ├── ObraSocialStrategy.php
│   │   ├── ParticularStrategy.php
│   │   ├── PrepaidStrategy.php
│   │   ├── PriceCalculator.php
│   │   └── PricingStrategy.php
│   │
│   ├── Reports/
│   │   ├── BasicReport.php
│   │   ├── DigitalSignatureDecorator.php
│   │   ├── PdfReportDecorator.php
│   │   ├── Report.php
│   │   ├── ReportDecorator.php
│   │   ├── ReportGenerator.php
│   │   └── WatermarkDecorator.php
│   │
│   ├── Repositories/
│   │   └── Repositorios de pedidos
│   │
│   ├── Security/
│   │   └── Protección CSRF
│   │
│   ├── Services/
│   │   ├── OrderService.php
│   │   └── OrderValidator.php
│   │
│   └── autoload.php
│
├── tests/
│   ├── AdapterTest.php
│   ├── DecoratorTest.php
│   ├── FacadeSrpTest.php
│   ├── FactoryTest.php
│   ├── MvcSrpTest.php
│   ├── MvcTest.php
│   ├── ObserverTest.php
│   ├── OrderTest.php
│   ├── PricingStrategyTest.php
│   ├── RepositoryStrategyTest.php
│   ├── SingletonTest.php
│   └── run.php
│
├── tools/
│   └── phpunit.phar
│
├── views/
│   ├── error.php
│   ├── order-form.php
│   ├── orders.php
│   └── report.php
│
├── .gitignore
├── ejecutar-pruebas.ps1
├── phpunit.xml
└── README.md
```

*La estructura representa los componentes incorporados en la rama. Algunos directorios pueden contener archivos adicionales.*

## 5. Patrones de diseño implementados

Se incorporaron diferentes patrones de diseño para resolver problemas específicos dentro del sistema.

### 5.1. Strategy – Estrategia

Se utiliza para separar las diferentes formas de calcular descuentos según el tipo de paciente o cobertura.

Estrategias implementadas:

- `ParticularStrategy`: atención particular, sin descuento.
- `ObraSocialStrategy`: descuento del 30 %.
- `JubiladoStrategy`: descuento del 50 %.
- `PrepaidStrategy`: descuento del 20 %.

La interfaz `PricingStrategy` establece el contrato que deben cumplir las estrategias.

`PriceCalculator` permite utilizar una estrategia sin depender directamente de una implementación concreta.

**Beneficio:** permite incorporar nuevas políticas de descuentos sin modificar toda la lógica del cálculo de precios.

### 5.2. Factory – Fábrica

Se utiliza para centralizar la creación de determinados objetos.

Se incorporaron fábricas para la construcción e integración de componentes, como las notificaciones y la configuración general de la aplicación.

Entre sus componentes se encuentran:

- `NotificationFactory`.
- `ApplicationFactory`.

**Beneficio:** evita distribuir la creación de objetos por diferentes partes del sistema y facilita la configuración de sus dependencias.

### 5.3. Adapter – Adaptador

Se utiliza para integrar componentes que tienen interfaces diferentes.

En este caso:

- `LegacyNotifier`: representa el notificador heredado.
- `LegacyNotifierAdapter`: adapta su funcionamiento para que pueda utilizarse mediante la interfaz actual.

**Beneficio:** permite reutilizar código existente sin tener que modificar directamente su implementación original.

### 5.4. Decorator – Decorador

Se utiliza para agregar funcionalidades adicionales a los reportes sin modificar su estructura original.

Componentes implementados:

- `Report`: contrato del reporte.
- `BasicReport`: implementación básica.
- `ReportDecorator`: clase base del decorador.
- `PdfReportDecorator`: agrega la representación de la funcionalidad PDF.
- `DigitalSignatureDecorator`: agrega la representación de una firma digital.
- `WatermarkDecorator`: agrega una marca de agua.

**Beneficio:** permite combinar funcionalidades adicionales de manera flexible.

**Nota:** las funcionalidades PDF y firma digital están representadas mediante decoradores en el sistema. No constituyen, por sí solas, la generación de un PDF real ni una firma criptográfica.

### 5.5. Observer – Observador

Se utiliza para notificar a diferentes componentes cuando ocurre un evento relacionado con un pedido.

Componentes principales:

- `OrderSubject`: administra los observadores.
- `OrderObserver`: define el contrato de los observadores.
- `EmailObserver`: representa las notificaciones por correo electrónico.
- `SmsObserver`: representa las notificaciones por SMS.
- `DashboardObserver`: representa la actualización del panel de control.
- `OrderEvents`: integra la gestión de eventos.

**Beneficio:** permite que diferentes componentes reaccionen ante un mismo evento sin que el objeto principal tenga que conocer todos sus detalles.

### 5.6. Facade – Fachada

Se utiliza para simplificar la interacción con diferentes componentes del sistema.

`OrderService` centraliza el proceso de gestión de pedidos, coordinando validaciones, cálculos, persistencia y notificaciones.

**Beneficio:** ofrece un punto de acceso más sencillo a operaciones que involucran varios componentes.

### 5.7. Singleton – Instancia única

Se utiliza en `Connection` para centralizar la instancia de conexión a la base de datos.

**Beneficio:** evita crear múltiples instancias independientes de la conexión cuando se utiliza el mismo administrador de conexiones.

### 5.8. Repository – Repositorio

Se utiliza para separar las operaciones de persistencia de la lógica del negocio.

Los repositorios administran las operaciones de almacenamiento y recuperación de pedidos.

**Beneficio:** permite que los servicios trabajen con abstracciones de persistencia, reduciendo su dependencia directa de las consultas SQL.

## 6. Otras mejoras incorporadas

### 6.1. Organización MVC

Se separaron las responsabilidades principales:

- **Modelo:** representa los datos y las reglas correspondientes a los pedidos.
- **Vista:** presenta los formularios, listados, errores y reportes.
- **Controlador:** recibe las solicitudes y coordina las operaciones necesarias.

Componentes principales:

- `Order`.
- `OrderController`.
- Vistas ubicadas en `views/`.

### 6.2. Validación de pedidos

Se incorporó `OrderValidator` para verificar los datos antes de procesar un pedido.

También se incorporaron validaciones en el modelo `Order`.

Entre los controles realizados se encuentran:

- Identificación del paciente.
- Importe válido.
- Importe positivo.
- Tipo de atención informado.
- Validaciones de los datos recibidos.

### 6.3. Seguridad CSRF

Se incorporó un mecanismo de protección contra ataques Cross-Site Request Forgery (CSRF).

La clase `Csrf` permite generar y verificar tokens de seguridad mediante funciones como:

- `random_bytes()`.
- `hash_equals()`.

El controlador verifica el token antes de procesar las operaciones protegidas.

### 6.4. Persistencia con PDO

Se incorporó una conexión mediante PDO para trabajar con la base de datos.

Se utilizan consultas preparadas para realizar operaciones de persistencia, reduciendo el riesgo de inyección SQL.

El esquema de base de datos se encuentra en:

```text
database/schema.sql
```

La configuración de ejemplo se encuentra en:

```text
config/database.example.php
```

### 6.5. Sistema de notificaciones

Se incorporaron diferentes tipos de notificaciones:

- Correo electrónico.
- SMS.
- WhatsApp.

También se incorporó `NotificationSender` para organizar el envío de notificaciones.

**Importante:** las implementaciones actuales representan notificaciones simuladas. No realizan envíos reales a servicios externos.

## 7. Pruebas automatizadas

Se incorporaron dos mecanismos de pruebas para verificar el funcionamiento de los componentes.

### 7.1. Pruebas con PHPUnit

Se utiliza PHPUnit 13.4.7 para ejecutar pruebas automatizadas.

Las pruebas se encuentran en el directorio:

```text
tests/
```

Se incorporaron pruebas para verificar:

- Adapter.
- Decorator.
- Factory.
- Facade y separación de responsabilidades.
- MVC y separación de responsabilidades.
- Observer.
- Modelo Order.
- Pricing Strategy.
- Repository.
- Singleton.

**Resultado de la ejecución local:**

| Indicador | Resultado |
|---|---:|
| Pruebas ejecutadas | 37 |
| Aserciones | 62 |
| Fallos | 0 |

### 7.2. Pruebas personalizadas

También se incorporó un sistema de pruebas independiente de PHPUnit:

```text
tests/run.php
```

Este archivo permite ejecutar verificaciones adicionales del proyecto.

**Resultado de la ejecución local:**

| Indicador | Resultado |
|---|---:|
| Pruebas correctas | 9 |
| Pruebas fallidas | 0 |

### 7.3. Documentación de pruebas

La documentación de los casos de prueba se encuentra en:

```text
docs/PRUEBAS.md
```

Este documento complementa las pruebas automatizadas y permite conocer los escenarios verificados.

## 8. Integración continua con GitHub Actions

Se incorporó un workflow para automatizar las verificaciones del proyecto.

Archivo:

```text
.github/workflows/php.yml
```

El workflow contempla:

1. Descarga del código del repositorio.
2. Preparación del entorno PHP 8.5.
3. Verificación de la versión de PHP.
4. Análisis de sintaxis de los archivos PHP.
5. Ejecución de PHPUnit.
6. Ejecución de las pruebas personalizadas.

La ejecución se configura para los siguientes eventos:

- Push a `main`.
- Push a `develop`.
- Push a ramas `feat/**`.
- Pull Requests dirigidos a `main` o `develop`.

PHPUnit se ejecuta directamente desde el archivo PHAR incluido en el proyecto:

```bash
php tools/phpunit.phar --configuration phpunit.xml
```

No se utiliza Composer para instalar PHPUnit en esta configuración.

## 9. Requisitos para ejecutar el proyecto

Antes de ejecutar el sistema se necesita:

- PHP compatible con las características utilizadas.
- PHP 8.5 para ejecutar PHPUnit 13.4.7.
- Extensiones PDO y PDO MySQL para la conexión a MySQL.
- MySQL.
- Git, si se desea clonar el repositorio.

Para las pruebas que utilizan SQLite también se necesita:

- `pdo_sqlite`.
- `sqlite3`.

## 10. Instalación y configuración

### Paso 1. Clonar el repositorio

```bash
git clone https://github.com/cuellaralex314-hola/laboratorio-pedidos-refactor.git
```

Ingresar al directorio:

```bash
cd laboratorio-pedidos-refactor
```

### Paso 2. Configurar la base de datos

Crear una base de datos MySQL y ejecutar el esquema SQL:

```text
database/schema.sql
```

### Paso 3. Configurar la conexión

Utilizar como referencia:

```text
config/database.example.php
```

Crear el archivo local:

```text
config/database.php
```

Completar los parámetros correspondientes a la conexión:

- Host.
- Nombre de la base de datos.
- Usuario.
- Contraseña.
- Puerto, si corresponde.

No publicar credenciales personales ni subir el archivo local de configuración al repositorio.

### Paso 4. Iniciar el servidor de desarrollo

Desde la raíz del proyecto:

```bash
php -S localhost:8000 -t public
```

Abrir en el navegador:

```text
http://localhost:8000
```

## 11. Ejecución de las pruebas

### Ejecutar PHPUnit

Con PHP 8.5:

```bash
php tools/phpunit.phar --configuration phpunit.xml
```

En Windows, si se utiliza la instalación global de PHP:

```powershell
C:\php\php.exe tools\phpunit.phar --configuration phpunit.xml
```

### Ejecutar las pruebas personalizadas

```bash
php tests/run.php
```

### Ejecutar las pruebas mediante el script de PowerShell

```powershell
.\ejecutar-pruebas.ps1
```

### Revisar la sintaxis PHP

En PowerShell:

```powershell
Get-ChildItem public,src,views,tests -Recurse -Filter *.php |
ForEach-Object {
    php -l $_.FullName
}
```

## 12. Control de versiones y organización de ramas

El trabajo de esta etapa se desarrolla en la rama:

```text
feat/ie3-testing
```

La rama principal del proyecto es:

```text
main
```

El objetivo es integrar las mejoras del IE3 mediante un Pull Request desde:

```text
feat/ie3-testing → main
```

De esta manera, los cambios se pueden revisar y validar antes de incorporarlos a la rama principal.

## 13. Resultados obtenidos

Durante esta etapa se incorporaron mejoras en diferentes áreas del proyecto:

- Implementación de patrones de diseño.
- Integración de componentes.
- Separación de responsabilidades.
- Validación de pedidos.
- Protección CSRF.
- Persistencia mediante PDO.
- Sistema de notificaciones.
- Generación y decoración de reportes.
- Pruebas automatizadas con PHPUnit.
- Pruebas personalizadas.
- Documentación de casos de prueba.
- Configuración de integración continua.

Las verificaciones locales realizadas obtuvieron los siguientes resultados:

| Verificación | Resultado |
|---|---|
| PHPUnit | 37 pruebas correctas |
| Aserciones PHPUnit | 62 |
| Pruebas personalizadas | 9 correctas |
| Pruebas personalizadas fallidas | 0 |
| Sintaxis PHP | Sin errores detectados |

Estos resultados corresponden a las ejecuciones locales realizadas durante el desarrollo. La validación definitiva de la integración continua se debe comprobar en GitHub Actions después de publicar la rama.

## 14. Autoría y finalidad académica

Este repositorio corresponde a un proyecto de laboratorio orientado a la aplicación práctica de conceptos de ingeniería y desarrollo de software.

El trabajo permite demostrar la utilización de patrones de diseño, buenas prácticas de programación, separación de responsabilidades, pruebas automatizadas y herramientas de integración continua.

---