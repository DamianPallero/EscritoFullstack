USE sigsm;

INSERT INTO documento (titulo, tipo, cedula_paciente, fecha_emision, ruta_archivo, activo) VALUES
('Indicacion de tratamiento', 'indicacion', '12345678', '2024-06-01 10:00:00', '/path/to/file1.pdf', 1),
('Informacion sobre procedimiento', 'informacion', '87654321', '2024-06-02 11:30:00', '/path/to/file2.pdf', 1),
('Informe de resultados', 'informacion', '11223344', '2024-06-03 09:15:00', '/path/to/file3.pdf', 1);