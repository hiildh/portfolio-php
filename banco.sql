CREATE DATABASE IF NOT EXISTS hilda;

USE hilda;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    celular VARCHAR(15) NOT NULL,
);
