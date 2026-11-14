USE sistema_estudiantes;

CREATE TABLE IF NOT EXISTS grupos (
  id_grupo INT AUTO_INCREMENT PRIMARY KEY,
  nombre_grupo VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NULL,
  horario VARCHAR(100) NULL,
  modalidad ENUM('presencial','en_linea','mixta') DEFAULT 'presencial',
  estado ENUM('activo','inactivo') DEFAULT 'activo',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS grupo_alumnos (
  id_grupo INT NOT NULL,
  id_usuario INT NOT NULL,
  fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_grupo,id_usuario),
  CONSTRAINT fk_ga_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE CASCADE,
  CONSTRAINT fk_ga_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS listas_alumnos (
  id_lista INT AUTO_INCREMENT PRIMARY KEY,
  nombre_lista VARCHAR(120) NOT NULL,
  descripcion VARCHAR(255) NULL,
  id_grupo INT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_lista_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lista_alumnos_detalle (
  id_lista INT NOT NULL,
  id_usuario INT NOT NULL,
  fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_lista,id_usuario),
  CONSTRAINT fk_lad_lista FOREIGN KEY (id_lista) REFERENCES listas_alumnos(id_lista) ON DELETE CASCADE,
  CONSTRAINT fk_lad_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;
