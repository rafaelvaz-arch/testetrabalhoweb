DROP USER IF EXISTS 'admin_php'@'127.0.0.1';
DROP USER IF EXISTS 'admin_php'@'localhost';

CREATE USER 'admin_php'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'ceub123456';
GRANT ALL PRIVILEGES ON *.* TO 'admin_php'@'127.0.0.1';

CREATE USER 'admin_php'@'localhost' IDENTIFIED WITH mysql_native_password BY 'ceub123456';
GRANT ALL PRIVILEGES ON *.* TO 'admin_php'@'localhost';

FLUSH PRIVILEGES;




USE clinica_vet;

DELETE FROM usuarios;

-- Insere o usuário administrador (Login: admin@vet.com | Senha: password)
INSERT INTO usuarios (nome, email, senha) VALUES 
('Administrador', 'admin@vet.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');