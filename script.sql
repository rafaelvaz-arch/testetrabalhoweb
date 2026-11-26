CREATE DATABASE IF NOT EXISTS clinica_vet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_vet;

-- 1. Tabela de Usuários (Autenticação)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL -- Armazenará o password_hash() do PHP
);

-- 2. Tabela de Espécies
CREATE TABLE especies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

-- 3. Tabela de Tutores
CREATE TABLE tutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20)
);

-- 4. Tabela de Veterinários
CREATE TABLE veterinarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    crmv VARCHAR(20) UNIQUE NOT NULL,
    especialidade VARCHAR(80)
);

-- 5. Tabela de Pets
CREATE TABLE pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    data_nascimento DATE,
    tutor_id INT NOT NULL,
    especie_id INT NOT NULL,
    FOREIGN KEY (tutor_id) REFERENCES tutores(id) ON DELETE CASCADE,
    FOREIGN KEY (especie_id) REFERENCES especies(id)
);

-- 6. Tabela de Consultas
CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_consulta DATETIME NOT NULL,
    descricao TEXT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    status ENUM('Agendada', 'Concluída', 'Cancelada') DEFAULT 'Agendada',
    pet_id INT NOT NULL,
    veterinario_id INT NOT NULL,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
    FOREIGN KEY (veterinario_id) REFERENCES veterinarios(id)
);

-- Carga Inicial (Login: rafael.vaz@sempreceub.com | Senha: rf123456)
INSERT INTO usuarios (nome, email, senha) VALUES 
('Rafael Machado', 'rafael.vaz@sempreceub.com', 'rf123456');

INSERT INTO especies (nome) VALUES ('Cachorro'), ('Gato'), ('Ave');

INSERT INTO tutores (nome, cpf, telefone) VALUES 
('Carlos Silva', '111.222.333-44', '(11) 99999-1111'),
('Mariana Costa', '555.666.777-88', '(11) 98888-2222');

INSERT INTO veterinarios (nome, crmv, especialidade) VALUES 
('Dra. Ana Souza', 'CRMV-SP 12345', 'Clínica Geral'),
('Dr. Pedro Lima', 'CRMV-SP 67890', 'Cirurgia');

INSERT INTO pets (nome, data_nascimento, tutor_id, especie_id) VALUES 
('Rex', '2021-05-10', 1, 1),
('Mia', '2022-08-15', 2, 2);

INSERT INTO consultas (data_consulta, descricao, valor, status, pet_id, veterinario_id) VALUES 
('2026-11-20 14:00:00', 'Vacinação anual e check-up geral', 180.00, 'Concluída', 1, 1),
('2026-11-26 10:30:00', 'Avaliação pré-operatória', 250.00, 'Agendada', 2, 2);