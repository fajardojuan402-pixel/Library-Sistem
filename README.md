Library System API

Este proyecto es un backend RESTful desarrollado en Laravel, para gestionar un sistema de biblioteca que incluye libros, autores, géneros, usuarios, préstamos y penalizaciones.

Tecnologías

PHP 8+

Laravel 10

MySQL / SQLite

PHPUnit para pruebas

Faker para datos de prueba

Funcionalidades
Gestión de libros

Crear, listar, actualizar y eliminar libros.

Cada libro tiene:

title, author_id, genre_id, isbn, total_copies, available_copies.

Al actualizar total_copies, se ajusta automáticamente available_copies considerando los préstamos activos.

Relación con:

Autor (1:N)

Género (1:N)

Préstamos (1:N)

Gestión de autores

Crear, listar y mostrar autores.

Cada autor puede tener muchos libros.

Gestión de géneros

Crear, listar y mostrar géneros.

Cada género puede tener muchos libros.

Gestión de usuarios

Crear, listar, actualizar y eliminar usuarios.

Cada usuario puede tener:

name, email (único), phone.

Relación con:

Préstamos (1:N)

Penalizaciones (1:N)

Préstamos

Crear préstamo de libro a un usuario.

Reglas de negocio:

Máximo 5 préstamos activos por usuario.

Solo se puede prestar si hay copias disponibles.

loan_date se asigna automáticamente.

due_date = loan_date + 14 días.

Devolver préstamo:

Actualiza return_date y status.

Incrementa available_copies del libro.

Penalizaciones

Penalizar préstamos vencidos no devueltos.

Cada penalización tiene:

amount fijo (5.000)

reason = "Préstamo vencido"

sent_at = fecha de envío

Notificación por correo al usuario.

No se permite duplicar penalización de un préstamo.

Estadísticas

Top 5 libros más prestados.

Disponibilidad de libros (% de copias disponibles).

Préstamos de los últimos 6 meses (por mes).

Usuarios penalizados y monto total de penalizaciones.

Modelo de datos
Author

id, name, bio

Relaciones:

books() → muchos libros

Genre

id, name

Relaciones:

books() → muchos libros

Book

id, title, author_id, genre_id, isbn, total_copies, available_copies

Relaciones:

author() → pertenece a un autor

genre() → pertenece a un género

loans() → muchos préstamos

User

id, name, email, phone

Relaciones:

loans() → muchos préstamos

penalties() → muchas penalizaciones

Loan

id, user_id, book_id, loan_date, due_date, return_date, status

Relaciones:

user() → pertenece a un usuario

book() → pertenece a un libro

penalties() → muchas penalizaciones

Penalty

id, loan_id, user_id, amount, reason, sent_at

Relaciones:

loan() → pertenece a un préstamo

user() → pertenece a un usuario

Endpoints
Libros
Método	Ruta	Acción
GET	/api/v1/books	Listar todos
POST	/api/v1/books	Crear libro
GET	/api/v1/books/{id}	Mostrar libro
PUT	/api/v1/books/{id}	Actualizar libro
DELETE	/api/v1/books/{id}	Eliminar libro
Autores y géneros
Recurso	Métodos
/api/v1/authors	GET, POST, GET/{id}
/api/v1/genres	GET, POST, GET/{id}
Usuarios
Método	Ruta	Acción
GET	/api/v1/users	Listar usuarios
POST	/api/v1/users	Crear usuario
GET	/api/v1/users/{id}	Mostrar usuario
Préstamos
Método	Ruta	Acción
POST	/api/v1/loans	Crear préstamo
PUT	/api/v1/loans/{loan}/return	Devolver libro
GET	/api/v1/loans	Listar préstamos
POST	/api/v1/loans/{id}/penalize	Penalizar préstamo vencido
Penalizaciones
Método	Ruta	Acción
GET	/api/v1/penalties	Listar penalizaciones
GET	/api/v1/penalties/{id}	Ver penalización
DELETE	/api/v1/penalties/{id}	Eliminar penalización
Estadísticas
Ruta	Acción
/api/v1/stats/top-books	Top libros prestados
/api/v1/stats/availability	Disponibilidad de libros
/api/v1/stats/loans-per-month	Préstamos últimos 6 meses
/api/v1/stats/penalties	Usuarios penalizados
Reglas de negocio

Máximo 5 préstamos activos por usuario.

Solo se puede prestar si available_copies > 0.

Al devolver préstamo:

return_date actualizado

status = "returned"

available_copies incrementado

Penalizaciones:

Monto fijo de 5.000

Notificación por correo

No duplicar penalización

Estadísticas y top libros siempre basados en los registros de préstamos.

Pruebas

Books: crear, actualizar, eliminar

Loans: crear préstamo, validar límite de copias

Penalties: penalizar préstamo correctamente

Ejecutar pruebas:

php artisan test

Instalación

Clonar el repositorio:

git clone <url-del-repositorio>
cd library-system


Instalar dependencias:

composer install


Configurar .env con la base de datos.

Migrar tablas:

php artisan migrate --seed


Ejecutar servidor:

php artisan serve
