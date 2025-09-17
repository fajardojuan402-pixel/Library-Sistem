
# 📚 Library System API

Backend **RESTful** desarrollado en **Laravel**, para gestionar un sistema de biblioteca que incluye **libros, autores, géneros, usuarios, préstamos y penalizaciones**.

---

## 🛠 Tecnologías

- PHP 8+
- Laravel 10
- MySQL / SQLite
- PHPUnit para pruebas
- Faker para generación de datos de prueba
- Mail para envío de penalizaciones

---

## ✨ Funcionalidades

- **Libros**
  - CRUD (Crear, Leer, Actualizar, Eliminar)
  - Control de copias totales y disponibles
  - Relación con autores y géneros

- **Usuarios**
  - CRUD
  - Relación con préstamos y penalizaciones

- **Préstamos**
  - Crear préstamo (máximo 5 activos por usuario)
  - Devolver préstamo
  - Actualiza la disponibilidad del libro automáticamente
  - Aplicar penalización si no se devuelve a tiempo

- **Penalizaciones**
  - Registrar multa por préstamo vencido
  - Evita duplicados de penalización

- **Estadísticas**
  - Top 5 libros más prestados
  - Disponibilidad de libros
  - Préstamos por mes (últimos 6 meses)
  - Usuarios penalizados y monto total de multas

---

## 🗂 Modelo de datos

| Modelo | Campos principales | Relaciones |
|--------|------------------|-----------|
| Author | id, name, bio | books() |
| Genre  | id, name | books() |
| Book   | id, title, author_id, genre_id, isbn, total_copies, available_copies | author(), genre(), loans() |
| User   | id, name, email, phone | loans(), penalties() |
| Loan   | id, user_id, book_id, loan_date, due_date, return_date, status | user(), book(), penalties() |
| Penalty| id, loan_id, user_id, amount, reason, sent_at | loan(), user() |

---

## 🚀 Endpoints principales

### 📚 Books
- `GET /api/v1/books` → Listar todos los libros con sus autores y géneros.
- `POST /api/v1/books` → Crear un nuevo libro.
- `PUT /api/v1/books/{id}` → Actualizar un libro existente.
- `DELETE /api/v1/books/{id}` → Eliminar un libro.

### ✍️ Authors
- `GET /api/v1/authors` → Listar todos los autores.
- `POST /api/v1/authors` → Crear un nuevo autor.
- `GET /api/v1/authors/{id}` → Obtener los detalles de un autor específico.

### 🎨 Genres
- `GET /api/v1/genres` → Listar todos los géneros.
- `POST /api/v1/genres` → Crear un nuevo género.
- `GET /api/v1/genres/{id}` → Obtener los detalles de un género específico.

### 👤 Users
- `GET /api/v1/users` → Listar todos los usuarios.
- `POST /api/v1/users` → Crear un nuevo usuario.
- `GET /api/v1/users/{id}` → Obtener los detalles de un usuario específico.

### 📖 Loans
- `POST /api/v1/loans` → Crear un nuevo préstamo (máximo 5 activos por usuario, se reduce la disponibilidad del libro).
- `PUT /api/v1/loans/{loan}/return` → Devolver un préstamo (actualiza la disponibilidad del libro y el estado del préstamo).
- `POST /api/v1/loans/{id}/penalize` → Aplicar penalización a un préstamo vencido y enviar correo al usuario.
- `GET /api/v1/loans` → Listar todos los préstamos con usuarios y libros relacionados.

### 💰 Penalties
- `GET /api/v1/penalties` → Listar todas las penalizaciones.
- `GET /api/v1/penalties/{id}` → Ver una penalización específica.
- `DELETE /api/v1/penalties/{id}` → Eliminar una penalización.

### 📊 Stats
- `GET /api/v1/stats/top-books` → Obtener los 5 libros más prestados.
- `GET /api/v1/stats/availability` → Ver estadísticas de disponibilidad de libros (copias totales, disponibles y porcentaje).
- `GET /api/v1/stats/loans-per-month` → Ver número de préstamos de los últimos 6 meses por mes.
- `GET /api/v1/stats/penalties` → Obtener estadísticas de penalizaciones (monto total y usuarios penalizados).

---

## 🧪 Pruebas

- **BookTest**
  - Crear libro
  - Actualizar libro
  - Eliminar libro

- **LoanTest**
  - Crear préstamo
  - Validar préstamo cuando no hay copias disponibles

- **PenaltyTest**
  - Aplicar penalización a un préstamo vencido

---

## 📦 Fábricas

- AuthorFactory
- GenreFactory
- BookFactory
- UserFactory
- LoanFactory

Estas permiten generar datos de prueba para tests y seeders.

---

## 📄 Migraciones

- authors
- genres
- books
- users
- loans
- penalties

---

## 🔧 Reglas de negocio

- Máximo 5 préstamos activos por usuario.

- Solo se puede prestar si available_copies > 0.

- Al devolver préstamo:

    - return_date actualizado

    - status = "returned"

    - available_copies incrementado

- Penalizaciones:

    - Monto fijo de 5.000

    - Notificación por correo

    - No duplicar penalización


- Estadísticas y top libros siempre basados en los registros de préstamos.  

---

