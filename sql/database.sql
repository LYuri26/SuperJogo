-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS superjogo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE superjogo;

-- Tabela de equipes
CREATE TABLE IF NOT EXISTS equipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    pontos INT DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (nome)
) ENGINE = InnoDB;

-- Tabela de membros
CREATE TABLE IF NOT EXISTS membros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipe_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    FOREIGN KEY (equipe_id) REFERENCES equipes(id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Tabela de perguntas
CREATE TABLE IF NOT EXISTS perguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pergunta TEXT NOT NULL,
    resposta_correta VARCHAR(255) NOT NULL,
    opcao1 VARCHAR(255) NOT NULL,
    opcao2 VARCHAR(255) NOT NULL,
    opcao3 VARCHAR(255) NOT NULL,
    opcao4 VARCHAR(255) NOT NULL,
    nivel_dificuldade ENUM('facil', 'medio', 'dificil') DEFAULT 'medio',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- Tabela de prendas (penalidades/bônus)
CREATE TABLE IF NOT EXISTS prendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao TEXT NOT NULL,
    tipo ENUM('penalidade', 'bonus') NOT NULL
) ENGINE = InnoDB;

-- Tabela de rodadas
CREATE TABLE IF NOT EXISTS rodadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pergunta_id INT NOT NULL,
    equipe_id INT NOT NULL,
    membro_id INT NOT NULL,
    resposta VARCHAR(255),
    acertou BOOLEAN DEFAULT FALSE,
    penalidade_equipe_id INT NULL,
    data_resposta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pergunta_id) REFERENCES perguntas(id),
    FOREIGN KEY (equipe_id) REFERENCES equipes(id),
    FOREIGN KEY (membro_id) REFERENCES membros(id),
    FOREIGN KEY (penalidade_equipe_id) REFERENCES equipes(id)
) ENGINE = InnoDB;