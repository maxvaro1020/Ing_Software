# Ing_Software

# Language School - Sistema de Gestión Académica

## Propósito
Sistema de gestión para la administración de alumnos, niveles, inscripciones, cupos, listas de espera, expedientes unificados, preguntas frecuentes y validación de pagos en la escuela de idiomas Language School.

## Integrantes del Equipo
- Pineda Teodosio Luis Eduardo
- Valle Rodriguez Maximiliano

## Stack Tecnológico y Versiones
- Lenguaje Backend: PHP 8.2
- Lenguaje Frontend: HTML5, CSS3
- Servidor Web: Apache 2.4 (XAMPP)
- Base de Datos: MySQL 8.0 / 8.3
- Conexión BD: PDO (PHP Data Objects)
- IDE: Visual Studio Code v1.93+

## Instrucciones de Instalación y Ejecución
1. Clonar este repositorio dentro de la carpeta local de Apache:
   `C:\xampp\htdocs\proyecto_estudiantes_php_mysql\`
2. Iniciar el panel de control de XAMPP y activar los servicios de **Apache** y **MySQL**.
3. Ingresar a phpMyAdmin (`http://localhost/phpmyadmin`) o MySQL Workbench y crear la base de datos:
   `CREATE DATABASE sistema_estudiantes;`
4. Importar el archivo script `schema.sql` ubicado en la raíz del proyecto para crear las tablas e índices.
5. Ajustar la contraseña de conexión MySQL en `includes/conexion.php` si es necesario.
6. Abrir en el navegador la URL principal:
   `http://localhost/proyecto_estudiantes_php_mysql/views/index.php`
