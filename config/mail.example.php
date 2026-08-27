<?php

declare(strict_types=1);

return [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'medicsaludbolivia@gmail.com',
    // Genera una contraseña de aplicación de Google y colócala solo en config/mail.php.
    'password' => '',
    'from_email' => 'medicsaludbolivia@gmail.com',
    'from_name' => 'MedicSalud Bolivia',
    'recipient_email' => 'cesar.sanjines@gmail.com',
    'recipient_name' => 'MedicSalud Citas',
];
