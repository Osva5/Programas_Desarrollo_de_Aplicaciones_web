# Reporte - Exposición: PHP + MySQL - CRUD de Usuarios con MySQLi y Bootstrap

---

**ASIGNATURA:**  
Desarrollo de Aplicaciones Web

**DOCENTE:**  
EUGENIA ERICA VERA CERVANTES

**ALUMNO:**  
MONTEALEGRE NAHUACATL OSVALDO 

**FECHA DE ENTREGA:**  
Martes, 23 de junio de 2026

---

## Introducción

Se presentan 5 archivos que implementan una aplicación web CRUD (Create, Read) básica para el registro de usuarios utilizando **PHP** con **MySQLi** (conexión procedural orientada a objetos) y **MySQL/MariaDB** como motor de base de datos. La interfaz está estilizada con **Bootstrap 5** para una experiencia responsive y moderna.

El objetivo principal es demostrar la conexión a una base de datos MySQL desde PHP mediante la extensión MySQLi, la ejecución de consultas preparadas (prepared statements) para prevenir inyecciones SQL, y la presentación de datos en una interfaz web limpia y funcional.

---

## Entorno y requisitos

Para visualizar y ejecutar correctamente los archivos de esta exposición se requiere lo siguiente:

- **Sistema operativo:** Windows 10/11, Linux o macOS.
- **XAMPP:** Se utiliza XAMPP como entorno de servidor web, el cual incluye Apache (servidor HTTP), PHP (intérprete) y MySQL/MariaDB (gestor de base de datos). Alternativamente puede usarse WAMP, MAMP o un stack LAMP.
- **Apache con PHP:** El módulo `mod_rewrite` no es necesario para este proyecto, pero PHP debe estar habilitado. XAMPP incluye PHP 8.x con la extensión `mysqli` activa por defecto.
- **MySQL/MariaDB:** Base de datos relacional. XAMPP incluye MariaDB (compatible con MySQL) accesible desde `phpMyAdmin` o mediante la línea de comandos.
- **Navegador web:** Cualquier navegador moderno (Chrome, Edge, Firefox) para acceder al formulario y al listado a través de HTTP.
- **Ubicación de los archivos:** Los archivos deben colocarse dentro del directorio `htdocs` de XAMPP (ej. `C:\xampp\htdocs\App_web\Exposicion\`).
- **Editor de código:** Recomendable usar VS Code, Notepad++ o cualquier editor de texto para revisar y modificar el código fuente.

---

## Configuración del entorno XAMPP

Para que los ejemplos funcionen correctamente con XAMPP, siga estos pasos:

1. Descargue e instale **XAMPP** desde el sitio oficial (`https://www.apachefriends.org/`).
2. Abra el **Panel de Control de XAMPP**.
3. Inicie los servicios **Apache** y **MySQL** haciendo clic en los botones "Start".
4. Verifique que Apache esté escuchando en el puerto 80 (o el puerto configurado) y MySQL en el puerto 3306.
5. Coloque los archivos de la exposición en `C:\xampp\htdocs\App_web\Exposicion\`.
6. Abra `phpMyAdmin` desde `http://localhost/phpmyadmin/` o ejecute el script `bd.sql` desde la línea de comandos de MySQL para crear la base de datos y la tabla.
7. Acceda a la aplicación desde `http://localhost/App_web/Exposicion/index.html`.

**Configuración recomendada de PHP (`php.ini`):**

| Directiva | Valor | Descripción |
|---|---|---|
| `extension=mysqli` | habilitada | Extensión MySQLi para conexión con MySQL |
| `display_errors` | `On` | Muestra errores de PHP durante el desarrollo (deshabilitar en producción) |
| `error_reporting` | `E_ALL` | Reporta todos los errores y advertencias |

---

## Estructura de la base de datos

**Archivo:** `bd.sql`

El script SQL crea la base de datos `mi_bd` y la tabla `usuarios` con la siguiente estructura:

| Campo | Tipo de dato | Descripción |
|---|---|---|
| `id` | `INT AUTO_INCREMENT` | Identificador único del registro (clave primaria) |
| `nombre` | `VARCHAR(100) NOT NULL` | Nombre completo del usuario |
| `email` | `VARCHAR(100) NOT NULL UNIQUE` | Correo electrónico (valor único) |
| `edad` | `TINYINT UNSIGNED NOT NULL` | Edad del usuario (0-255) |

Además, inserta 3 registros de ejemplo: Juan Pérez, María García y Carlos López.

```sql
CREATE DATABASE IF NOT EXISTS mi_bd
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE mi_bd;

CREATE TABLE IF NOT EXISTS usuarios (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    email   VARCHAR(100) NOT NULL UNIQUE,
    edad    TINYINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO usuarios (nombre, email, edad) VALUES
    ('Juan Pérez',   'juan@example.com',   28),
    ('María García', 'maria@example.com',  32),
    ('Carlos López', 'carlos@example.com', 25);
```

---

## Archivos de la exposición

La exposición contiene los siguientes archivos:

| Archivo | Lenguaje | Descripción |
|---|---|---|
| `bd.sql` | SQL | Script de creación de la base de datos, tabla `usuarios` y registros de ejemplo |
| `conexion.php` | PHP | Conexión a la base de datos MySQL mediante MySQLi |
| `index.html` | HTML + Bootstrap | Formulario de registro de nuevos usuarios |
| `registrar.php` | PHP | Procesa el formulario e inserta el usuario con prepared statements |
| `listar.php` | PHP | Consulta y muestra todos los usuarios registrados en una tabla |

---

## Ejecución

Para ejecutar los ejemplos de esta exposición siga estos pasos:

1. Asegúrese de que XAMPP esté instalado y que Apache y MySQL estén en ejecución.
2. Copie todos los archivos al directorio del sitio web (`C:\xampp\htdocs\App_web\Exposicion\`).
3. Ejecute el script `bd.sql` desde `phpMyAdmin` o desde la línea de comandos de MySQL para crear la base de datos y la tabla.
4. Abra un navegador web y acceda a la siguiente URL:
    - `http://localhost/App_web/Exposicion/index.html` — formulario de registro
    - `http://localhost/App_web/Exposicion/listar.php` — listado de usuarios
5. Complete el formulario y envíelo para registrar un nuevo usuario.

---

## Archivo: `conexion.php`

**Ruta:** `conexion.php`

Este archivo establece la conexión con la base de datos MySQL utilizando la clase `mysqli` de PHP. Es incluido desde `registrar.php` y `listar.php` mediante `require_once`.

```php
<?php
$conexion = new mysqli("localhost", "root", "", "mi_bd");
$conexion->set_charset("utf8");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
```

**Explicación del código:**

| Elemento | Descripción |
|---|---|
| `new mysqli("localhost", "root", "", "mi_bd")` | Crea una nueva conexión MySQLi. Parámetros: servidor, usuario, contraseña (vacía en XAMPP por defecto), nombre de base de datos. |
| `set_charset("utf8")` | Establece el conjunto de caracteres UTF-8 para la comunicación con la base de datos. |
| `$conexion->connect_error` | Propiedad que contiene el mensaje de error si la conexión falla. |
| `die(...)` | Detiene la ejecución y muestra el mensaje de error. |

**Nota:** En un entorno de producción, las credenciales de conexión deben almacenarse en un archivo de configuración separado fuera del directorio web y nunca deben incluirse directamente en el código.

---

## Archivo: `index.html`

**Ruta:** `index.html`

Página principal que contiene un formulario HTML estilizado con Bootstrap 5. Los campos solicitados son: nombre, email y edad. El formulario utiliza el método `POST` y envía los datos a `registrar.php`.

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title h4 mb-4">Registro de usuarios</h1>
                        <form action="registrar.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Edad</label>
                                <input type="number" name="edad" class="form-control" min="1" max="120" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrar</button>
                        </form>
                        <p class="mt-3 mb-0 text-center">
                            <a href="listar.php">Ver usuarios registrados</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

**Explicación del código:**

| Elemento | Descripción |
|---|---|
| `Bootstrap 5 CDN` | Hoja de estilos CSS desde `cdn.jsdelivr.net` para diseño responsive. |
| `class="bg-light"` | Fondo gris claro en el body. |
| `container py-5` | Contenedor con padding vertical de 5 unidades Bootstrap. |
| `card shadow-sm` | Tarjeta con sombra ligera para contener el formulario. |
| `form action="registrar.php" method="POST"` | Envía los datos a `registrar.php` mediante POST. |
| `input type="email"` | Validación nativa del navegador para formato de correo. |
| `input type="number" min="1" max="120"` | Campo numérico con rango válido de edad. |
| `required` | Validación nativa del navegador: el campo no puede estar vacío. |

---

## Archivo: `registrar.php`

**Ruta:** `registrar.php`

Este archivo recibe los datos enviados desde `index.html` mediante POST, sanitiza las entradas, e inserta el nuevo usuario en la base de datos utilizando **prepared statements** (consultas preparadas) para prevenir inyecciones SQL. Muestra un mensaje de éxito con los datos registrados o un mensaje de error en caso de fallo.

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <?php
                        require_once 'conexion.php';

                        $nombre = htmlspecialchars($_POST['nombre']);
                        $email  = htmlspecialchars($_POST['email']);
                        $edad   = (int)$_POST['edad'];

                        $sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (?, ?, ?)";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("ssi", $nombre, $email, $edad);

                        if ($stmt->execute()) { ?>
                            <h2 class="h4 text-success mb-3">Usuario registrado exitosamente</h2>
                            <p class="mb-1"><strong>Nombre:</strong> <?= $nombre ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= $email ?></p>
                            <p class="mb-3"><strong>Edad:</strong> <?= $edad ?></p>
                            <a href="index.html" class="btn btn-primary">Volver al formulario</a>
                            <a href="listar.php" class="btn btn-outline-secondary">Ver todos</a>
                        <?php } else { ?>
                            <h2 class="h4 text-danger mb-3">Error</h2>
                            <p><?= $stmt->error ?></p>
                            <a href="index.html" class="btn btn-primary">Volver</a>
                        <?php }

                        $stmt->close();
                        $conexion->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

**Explicación del código:**

| Elemento | Descripción |
|---|---|
| `require_once 'conexion.php'` | Incluye el archivo de conexión a la base de datos. |
| `htmlspecialchars($_POST['nombre'])` | Sanitiza la entrada convirtiendo caracteres especiales HTML a entidades. |
| `(int)$_POST['edad']` | Convierte el valor a entero para asegurar el tipo de dato. |
| `$conexion->prepare($sql)` | Prepara la consulta SQL con marcadores de posición `?`. |
| `$stmt->bind_param("ssi", ...)` | Vincula los parámetros: "s" = string, "i" = integer. |
| `$stmt->execute()` | Ejecuta la consulta preparada. |
| `<?= $variable ?>` | Etiqueta PHP abreviada equivalente a `<?php echo $variable; ?>`. |
| `$stmt->close(); $conexion->close()` | Cierra la sentencia preparada y la conexión. |

**Ventajas de los prepared statements:**

| Beneficio | Descripción |
|---|---|
| **Seguridad** | Previene inyecciones SQL al separar los datos de la estructura SQL. |
| **Rendimiento** | La consulta se compila una sola vez y puede ejecutarse múltiples veces con distintos parámetros. |
| **Legibilidad** | El código es más limpio al no tener que escapar manualmente las cadenas. |

---

## Archivo: `listar.php`

**Ruta:** `listar.php`

Este archivo consulta todos los registros de la tabla `usuarios` y los muestra en una tabla HTML responsiva con estilo Bootstrap. Los resultados se ordenan por `id` de forma descendente (los más recientes primero).

```php
<?php
require_once 'conexion.php';

$sql = "SELECT id, nombre, email, edad FROM usuarios ORDER BY id DESC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios registrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-4">Usuarios registrados</h1>
                <?php if ($resultado->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th><th>Nombre</th><th>Email</th><th>Edad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fila = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $fila['id'] ?></td>
                                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                        <td><?= htmlspecialchars($fila['email']) ?></td>
                                        <td><?= $fila['edad'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">No hay usuarios registrados todavía.</div>
                <?php endif; ?>
                <p class="mt-3 mb-0">
                    <a href="index.html" class="btn btn-primary">Registrar nuevo usuario</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
```

**Explicación del código:**

| Elemento | Descripción |
|---|---|
| `$conexion->query($sql)` | Ejecuta la consulta SELECT y devuelve un objeto `mysqli_result`. |
| `ORDER BY id DESC` | Ordena los registros del más reciente al más antiguo. |
| `$resultado->num_rows` | Propiedad que contiene el número de filas devueltas. |
| `$resultado->fetch_assoc()` | Obtiene cada fila como un array asociativo (clave = nombre de columna). |
| `table table-striped table-hover` | Clases Bootstrap: filas alternadas + efecto hover. |
| `htmlspecialchars($fila['nombre'])` | Escapa caracteres HTML para prevenir XSS al mostrar datos. |
| `table-responsive` | Hace que la tabla se desplace horizontalmente en pantallas pequeñas. |

---

## Conclusión

En esta exposición se ha demostrado cómo PHP junto con MySQL puede implementar las operaciones básicas CRUD (Crear y Leer) sobre una base de datos relacional utilizando la extensión MySQLi. Se implementaron buenas prácticas de seguridad como el uso de **prepared statements** para prevenir inyecciones SQL y la sanitización de salidas con `htmlspecialchars()` para mitigar ataques XSS. La interfaz de usuario se construyó con **Bootstrap 5**, proporcionando un diseño moderno, responsive y accesible. Este conjunto de archivos sirve como base para aplicaciones web más complejas que requieran autenticación, actualización y eliminación de registros, así como validación avanzada tanto del lado del cliente como del servidor.
