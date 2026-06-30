# Manual de instalación de la Base de Datos — ForzaSoccer

## 1. Requisitos

- MySQL / MariaDB instalado y en ejecución
- Acceso a línea de comandos (`mysql`) o a phpMyAdmin
- PHP 8.x con extensión `pdo_mysql` habilitada

## 2. Crear la base de datos e importar el esquema

### Opción A — Desde terminal (recomendado)

```bash
mysql -u root -p < database/schema.sql
```

Esto ejecuta todo el archivo `database/schema.sql` que:
- Crea la base de datos `canchas_deportivas` (si no existe)
- Crea las 7 tablas: `usuarios`, `canchas`, `precios`, `horarios`, `reservaciones`, `pagos`, `historial`
- Inserta datos de ejemplo:
  - 1 admin (`admin@canchas.com`, contraseña: `password`)
  - 9 canchas deportivas
  - 27 registros de precios (regular / pico / finde)
  - Horarios disponibles de 08:00 a 22:00 para todas las canchas (todos los días)

### Opción B — Desde phpMyAdmin

1. Abre phpMyAdmin en tu navegador
2. Ve a la pestaña **Importar**
3. Selecciona el archivo `database/schema.sql`
4. Haz clic en **Continuar**

### Opción C — Paso a paso (manual)

```bash
mysql -u root -p
```

```sql
CREATE DATABASE IF NOT EXISTS canchas_deportivas
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE canchas_deportivas;

SOURCE /ruta/completa/hacia/Proyecto_final/database/schema.sql;

EXIT;
```

## 3. Verificar la importación

```bash
mysql -u root -p -e "USE canchas_deportivas; SHOW TABLES;"
```

Deberías ver las 7 tablas:
```
historial
canchas
horarios
pagos
precios
reservaciones
usuarios
```

## 4. Configurar la conexión en el proyecto

Edita `config/config.php` con los datos de tu servidor MySQL:

```php
define('DB_HOST', 'localhost');   // 127.0.0.1 si prefieres IP loopback
define('DB_NAME', 'canchas_deportivas');
define('DB_USER', 'root');        // o el usuario que uses (ej. 'expositor')
define('DB_PASS', '');            // contraseña de tu usuario MySQL
define('DB_CHARSET', 'utf8mb4');
```

### Diferencia con `Exposicion/conexion.php`

| Aspecto | Exposición (`conexion.php`) | Proyecto Final (`config/database.php`) |
|---------|-----------------------------|----------------------------------------|
| Extensión | `mysqli` (MySQLi) | `PDO` |
| Servidor | `127.0.0.1` | `localhost` |
| Usuario | `expositor` | `root` |
| Contraseña | `exponemos123!!` | (vacía) |
| Base de datos | `mi_bd` | `canchas_deportivas` |
| Charset | `utf8` | `utf8mb4` |

Ambos métodos funcionan, pero el proyecto usa **PDO** por ser más seguro y portable. Si tu servidor usa un usuario distinto a `root` (como `expositor`), simplemente cambia `DB_USER` y `DB_PASS` en `config/config.php`.

## 5. Probar la conexión

Puedes crear un archivo `test_db.php` temporal en la raíz del proyecto:

```php
<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::conectar();
    echo "✅ Conexión exitosa a la base de datos 'canchas_deportivas'";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
```

Ábrelo en el navegador o ejecútalo con PHP CLI:

```bash
php test_db.php
```

Si ves el mensaje de éxito, todo está listo. Si hay error, revisa:
- Que MySQL esté corriendo (`sudo systemctl status mysql`)
- Que el usuario y contraseña en `config.php` sean correctos
- Que la base de datos `canchas_deportivas` exista
- Que la extensión `pdo_mysql` esté habilitada en PHP

## 6. Acceso inicial al sistema

| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | `admin@canchas.com` | `password` |

Puedes registrar nuevos usuarios desde la página de registro del sistema.
