# Operadora Tours ETAI

Sistema web desarrollado para la gestión de una operadora turística, como proyecto de desarrollo de software.

La aplicación permite centralizar diferentes procesos relacionados con la gestión de clientes, agencias, servicios, reservas y operaciones de una operadora turística.

##  Tecnologías utilizadas

* PHP
* Laravel
* MySQL
* Blade
* Bootstrap
* JavaScript
* Git
* GitHub

##  Funcionalidades

### Gestión de usuarios

* Autenticación de usuarios.
* Gestión de roles.
* Control de acceso según el tipo de usuario.
* Recuperación y restablecimiento de contraseña.

### Gestión de servicios

* Registro y administración de servicios turísticos.
* Gestión de destinos.
* Categorías de servicios.
* Configuración de precios.
* Manejo de diferentes modalidades de precio.

### Gestión de clientes y agencias

* Registro y administración de clientes.
* Gestión de agencias.
* Asociación de información relacionada con las reservas.

### Gestión de reservas

* Creación y administración de reservas.
* Gestión de pasajeros.
* Asociación de servicios y clientes.
* Seguimiento del estado de las reservas.

### Gestión de operaciones

* Creación de operaciones a partir de reservas.
* Asignación de guías.
* Asignación de choferes.
* Gestión de transporte.
* Validación de información relacionada con documentos y vencimientos.

### Calendario

* Visualización de reservas y operaciones mediante calendario.
* Organización de actividades según fecha.

### Generación de documentos

* Generación de documentos PDF relacionados con los procesos del sistema.

##  Base de datos

El proyecto utiliza MySQL como sistema de gestión de base de datos.

La estructura se administra mediante migraciones de Laravel y el acceso a los datos se realiza mediante Eloquent ORM.

##  Arquitectura

El proyecto está desarrollado utilizando el patrón MVC proporcionado por Laravel.

* **Models:** gestión de entidades y acceso a datos.
* **Views:** interfaces desarrolladas con Blade.
* **Controllers:** manejo de la lógica de las diferentes funcionalidades.
* **Routes:** definición de las rutas de la aplicación.
* **Migrations:** creación y modificación de la estructura de la base de datos.

##  Objetivo

El objetivo del proyecto es desarrollar una solución web que permita centralizar y mejorar la gestión de los principales procesos de una operadora turística.

## 📸 Capturas de pantalla

### Inicio de sesión

![operaciones](screenshots/operaciones.png)

### Gestión de servicios

![Servicios](screenshots/dashboard.PNG)

### Gestión de reservas

![Reservas](screenshots/reservas.PNG)

##  Autor

**Osvaldo Daniel Salazar Oporta**
