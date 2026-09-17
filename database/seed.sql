USE clinica_vet;

-- Senha: admin123 (gerada com password_hash, PASSWORD_DEFAULT)
INSERT INTO usuarios (nome, email, senha, perfil) VALUES
('Administrador', 'admin@clinica.test', '$2y$10$examplehashsubstitutedepoisdegerarcomphp', 'admin');
