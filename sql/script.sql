-- script.sql

CREATE DATABASE inventario_db;
USE inventario_db;

CREATE TABLE almacenes (
    id INT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    ubicacion VARCHAR(255) NOT NULL
);

CREATE TABLE productos (
    id INT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE inventario (
    almacen_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL
);
