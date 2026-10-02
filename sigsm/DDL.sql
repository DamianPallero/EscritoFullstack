DROP DATABASE IF EXISTS sigsm;

CREATE DATABASE sigsm;

CREATE TABLE documento (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(150) NOT NULL,
tipo ENUM('indicacion', 'informacion') NOT NULL,
cedula_paciente VARCHAR(8) NOT NULL,
fecha_emision DATETIME NOT NULL,
ruta_archivo VARCHAR(255) NOT NULL UNIQUE,
activo TINYINT(1) DEFAULT 1
);