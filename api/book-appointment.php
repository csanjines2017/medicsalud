<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require dirname(__DIR__) . '/vendor/autoload.php';

$mailConfigPath = dirname(__DIR__) . '/config/mail.php';
if (!is_file($mailConfigPath)) {
    error_log('MedicSalud: mail configuration file is missing.');
    respond(500, ['ok' => false, 'message' => 'No fue posible procesar tu solicitud.']);
}

/** @var array<string, mixed> $mailConfig */
$mailConfig = require $mailConfigPath;

header('Content-Type: application/json; charset=utf-8');

/** @param array<string, mixed> $payload */
function respond(int $status, array $payload): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['ok' => false, 'message' => 'Método no permitido.']);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$input = $_POST;

if (str_contains($contentType, 'application/json')) {
    $decoded = json_decode((string) file_get_contents('php://input'), true);
    if (!is_array($decoded)) {
        respond(400, ['ok' => false, 'message' => 'Los datos enviados no son válidos.']);
    }
    $input = $decoded;
}

$name = trim((string) ($input['name'] ?? ''));
$phone = trim((string) ($input['phone'] ?? ''));
$therapy = trim((string) ($input['therapy'] ?? ''));
$message = trim((string) ($input['msg'] ?? ''));

if ($name === '' || $phone === '') {
    respond(422, ['ok' => false, 'message' => 'Nombre y medio de contacto son obligatorios.']);
}

if (mb_strlen($name) > 120 || mb_strlen($phone) > 160 || mb_strlen($therapy) > 120 || mb_strlen($message) > 2000) {
    respond(422, ['ok' => false, 'message' => 'Uno de los campos supera el límite permitido.']);
}

$smtpPassword = trim((string) ($mailConfig['password'] ?? ''));
if ($smtpPassword === '') {
    error_log('MedicSalud: SMTP password is not configured.');
    respond(500, ['ok' => false, 'message' => 'No fue posible procesar tu solicitud.']);
}

$therapyLabels = [
    'ozono' => 'Ozonoterapia',
    'prp' => 'Plasma rico en plaquetas (PRP)',
    'otra' => 'Aún no lo sé',
];
$therapyName = $therapyLabels[$therapy] ?? ($therapy !== '' ? $therapy : 'No especificada');

try {
    $mailer = new PHPMailer(true);
    $mailer->isSMTP();
    $mailer->Host = (string) ($mailConfig['host'] ?? 'smtp.gmail.com');
    $mailer->SMTPAuth = true;
    $mailer->Username = (string) ($mailConfig['username'] ?? '');
    $mailer->Password = $smtpPassword;
    $mailer->SMTPSecure = ($mailConfig['encryption'] ?? 'tls') === 'ssl'
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port = (int) ($mailConfig['port'] ?? 587);
    $mailer->CharSet = PHPMailer::CHARSET_UTF8;
    $mailer->setFrom((string) ($mailConfig['from_email'] ?? $mailer->Username), (string) ($mailConfig['from_name'] ?? 'MedicSalud Bolivia'));
    $mailer->addAddress((string) ($mailConfig['recipient_email'] ?? ''), (string) ($mailConfig['recipient_name'] ?? 'MedicSalud Citas'));
    $mailer->addReplyTo((string) ($mailConfig['from_email'] ?? $mailer->Username), (string) ($mailConfig['from_name'] ?? 'MedicSalud Bolivia'));
    $mailer->isHTML(false);
    $mailer->Subject = 'Nueva solicitud de cita - MedicSalud';
    $mailer->Body = implode(PHP_EOL, [
        'Nueva solicitud de cita desde MedicSalud.',
        '',
        'Nombre: ' . $name,
        'Contacto: ' . $phone,
        'Terapia de interés: ' . $therapyName,
        'Mensaje: ' . ($message !== '' ? $message : 'Sin mensaje adicional'),
    ]);
    $mailer->send();
} catch (Exception $exception) {
    error_log('MedicSalud: appointment email could not be sent. ' . $exception->getMessage());
    respond(500, ['ok' => false, 'message' => 'No fue posible procesar tu solicitud.']);
}

respond(200, ['ok' => true]);
