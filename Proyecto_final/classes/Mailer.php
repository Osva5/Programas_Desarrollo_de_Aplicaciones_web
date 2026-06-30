<?php
require_once __DIR__ . '/../config/config.php';

class Mailer
{
    public function enviar($para, $asunto, $cuerpoHtml)
    {
        $mensaje = $this->armarMensaje($cuerpoHtml);
        return $this->enviarSMTP($para, $asunto, $mensaje);
    }

    private function armarMensaje($cuerpoHtml)
    {
        return '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><style>
            body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #0d6efd, #0056b3); color: white; padding: 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .body { padding: 30px; }
            .body p { line-height: 1.6; color: #333; }
            .info { background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 15px; margin: 15px 0; border-radius: 4px; }
            .footer { background: #212529; color: #adb5bd; text-align: center; padding: 20px; font-size: 12px; }
            .btn { display: inline-block; padding: 12px 24px; background: #0d6efd; color: white; text-decoration: none; border-radius: 8px; margin: 10px 0; }
        </style></head><body>
            <div class="container">
                <div class="header"><h1>' . SITE_NAME . '</h1></div>
                <div class="body">' . $cuerpoHtml . '</div>
                <div class="footer">&copy; ' . date('Y') . ' ' . SITE_NAME . ' - Todos los derechos reservados</div>
            </div>
        </body></html>';
    }

    private function enviarSMTP($para, $asunto, $mensaje)
    {
        $boundary = 'boundary_' . md5(uniqid('', true));

        $cabeceras = "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-Type: text/html; charset=UTF-8\r\n";
        $cabeceras .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
        $cabeceras .= "To: " . $para . "\r\n";
        $cabeceras .= "Subject: " . $asunto . "\r\n";
        $cabeceras .= "Date: " . date('r') . "\r\n";
        $cabeceras .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $cuerpoCompleto = $cabeceras . "\r\n" . $mensaje;

        $errno = 0;
        $errstr = '';
        $protocolo = defined('MAIL_SMTP_SECURE') && MAIL_SMTP_SECURE === 'tls' ? 'tcp' : 'tcp';
        $puerto = defined('MAIL_PORT') ? MAIL_PORT : 587;

        $conexion = @fsockopen($protocolo . '://' . MAIL_HOST, $puerto, $errno, $errstr, 30);
        if (!$conexion) {
            error_log("Mailer: No se pudo conectar a " . MAIL_HOST . ":$puerto - $errstr");
            return false;
        }

        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '220') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, "EHLO " . gethostname() . "\r\n");
        $respuesta = $this->leerRespuesta($conexion);

        if (defined('MAIL_SMTP_SECURE') && MAIL_SMTP_SECURE === 'tls') {
            fputs($conexion, "STARTTLS\r\n");
            $respuesta = fgets($conexion, 515);
            if (substr($respuesta, 0, 3) !== '220') {
                fclose($conexion);
                return false;
            }
            stream_socket_enable_crypto($conexion, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

            fputs($conexion, "EHLO " . gethostname() . "\r\n");
            $this->leerRespuesta($conexion);
        }

        fputs($conexion, "AUTH LOGIN\r\n");
        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '334') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, base64_encode(MAIL_USER) . "\r\n");
        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '334') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, base64_encode(MAIL_PASS) . "\r\n");
        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '235') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, "MAIL FROM:<" . MAIL_FROM . ">\r\n");
        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '250') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, "RCPT TO:<" . $para . ">\r\n");
        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '250') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, "DATA\r\n");
        $respuesta = fgets($conexion, 515);
        if (substr($respuesta, 0, 3) !== '354') {
            fclose($conexion);
            return false;
        }

        fputs($conexion, $cuerpoCompleto . "\r\n.\r\n");
        $respuesta = fgets($conexion, 515);

        fputs($conexion, "QUIT\r\n");
        fclose($conexion);

        return substr($respuesta, 0, 3) === '250';
    }

    private function leerRespuesta($conexion)
    {
        $respuesta = '';
        while ($linea = fgets($conexion, 515)) {
            $respuesta .= $linea;
            if (isset($linea[3]) && $linea[3] === ' ') {
                break;
            }
        }
        return $respuesta;
    }

    public function bienvenida($nombre, $email)
    {
        $asunto = '¡Bienvenido a ' . SITE_NAME . '!';
        $cuerpo = '<h2>¡Hola ' . htmlspecialchars($nombre) . '!</h2>
            <p>Gracias por registrarte en <strong>' . SITE_NAME . '</strong>.</p>
            <p>Ahora puedes explorar nuestras canchas y reservar tu horario favorito.</p>
            <div class="info">
                <p><strong>Tu correo:</strong> ' . htmlspecialchars($email) . '</p>
                <p><strong>Fecha de registro:</strong> ' . date('d/m/Y H:i') . '</p>
            </div>
            <p style="text-align:center;"><a href="' . SITE_URL . '/pages/canchas.php" class="btn">Ver Canchas</a></p>
            <p>Si tienes dudas, contáctanos.</p>
            <p>¡Disfruta del deporte!</p>';
        return $this->enviar($email, $asunto, $cuerpo);
    }

    public function confirmacionReservacion($nombre, $email, $cancha, $fecha, $horaInicio, $horaFin, $total, $referencia)
    {
        $asunto = 'Reservación Confirmada - ' . SITE_NAME;
        $cuerpo = '<h2>¡Reservación Confirmada, ' . htmlspecialchars($nombre) . '!</h2>
            <p>Tu reservación ha sido confirmada exitosamente.</p>
            <div class="info">
                <p><strong>Cancha:</strong> ' . htmlspecialchars($cancha) . '</p>
                <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($fecha)) . '</p>
                <p><strong>Horario:</strong> ' . substr($horaInicio, 0, 5) . ' - ' . substr($horaFin, 0, 5) . '</p>
                <p><strong>Total pagado:</strong> $' . number_format($total, 2) . '</p>
                <p><strong>Referencia:</strong> ' . htmlspecialchars($referencia) . '</p>
            </div>
            <p style="text-align:center;"><a href="' . SITE_URL . '/pages/mis_reservaciones.php" class="btn">Mis Reservaciones</a></p>
            <p>Te esperamos. ¡Disfruta tu partido!</p>';
        return $this->enviar($email, $asunto, $cuerpo);
    }

    public function nuevaReservacionAdmin($adminEmail, $usuarioNombre, $cancha, $fecha, $horaInicio, $horaFin)
    {
        $asunto = 'Nueva Reservación - ' . SITE_NAME;
        $cuerpo = '<h2>Nueva Reservación Realizada</h2>
            <div class="info">
                <p><strong>Usuario:</strong> ' . htmlspecialchars($usuarioNombre) . '</p>
                <p><strong>Cancha:</strong> ' . htmlspecialchars($cancha) . '</p>
                <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($fecha)) . '</p>
                <p><strong>Horario:</strong> ' . substr($horaInicio, 0, 5) . ' - ' . substr($horaFin, 0, 5) . '</p>
            </div>
            <p style="text-align:center;"><a href="' . SITE_URL . '/admin/reservaciones.php" class="btn">Ver en Admin</a></p>';
        return $this->enviar($adminEmail, $asunto, $cuerpo);
    }

    public function cancelacionReservacion($nombre, $email, $cancha, $fecha, $horaInicio, $horaFin)
    {
        $asunto = 'Reservación Cancelada - ' . SITE_NAME;
        $cuerpo = '<h2>Reservación Cancelada, ' . htmlspecialchars($nombre) . '</h2>
            <p>Tu reservación ha sido cancelada.</p>
            <div class="info">
                <p><strong>Cancha:</strong> ' . htmlspecialchars($cancha) . '</p>
                <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($fecha)) . '</p>
                <p><strong>Horario:</strong> ' . substr($horaInicio, 0, 5) . ' - ' . substr($horaFin, 0, 5) . '</p>
            </div>
            <p>Si requieres más información, contáctanos.</p>
            <p style="text-align:center;"><a href="' . SITE_URL . '/pages/mis_reservaciones.php" class="btn">Mis Reservaciones</a></p>';
        return $this->enviar($email, $asunto, $cuerpo);
    }

    public function recuperacionPassword($nombre, $email, $token)
    {
        $asunto = 'Recuperación de Contraseña - ' . SITE_NAME;
        $enlace = SITE_URL . '/pages/restablecer.php?token=' . urlencode($token);
        $cuerpo = '<h2>Hola ' . htmlspecialchars($nombre) . '</h2>
            <p>Recibimos una solicitud para restablecer tu contraseña en <strong>' . SITE_NAME . '</strong>.</p>
            <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
            <p style="text-align:center;"><a href="' . $enlace . '" class="btn">Restablecer Contraseña</a></p>
            <p>Si no solicitaste esto, ignora este mensaje.</p>
            <div class="info">
                <p><strong>Este enlace expira en 1 hora.</strong></p>
            </div>
            <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
            <p style="font-size:12px; word-break:break-all;">' . htmlspecialchars($enlace) . '</p>';
        return $this->enviar($email, $asunto, $cuerpo);
    }
}