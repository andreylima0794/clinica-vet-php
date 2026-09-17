<?php
// Copie este arquivo para config/config.php e preencha com os dados reais.
// config/config.php NÃO deve ser versionado (já está no .gitignore).

return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'clinica_vet',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],

    'app' => [
        'name' => 'Clínica Vet',
        'url' => 'http://localhost:8000',
        'session_name' => 'clinica_vet_session',
    ],
];
