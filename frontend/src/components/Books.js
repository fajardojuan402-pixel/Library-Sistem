import React, { useEffect, useState } from 'react';
import api from '../api';
import BookForm from './BookForm';

export default function Books() {
  const [books, setBooks] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [editing, setEditing] = useState(null);

  useEffect(() => {
    fetchBooks();
  }, []);

  async function fetchBooks() {
    try {
      setLoading(true);
      setError(null);
      const res = await api.get('/books');
      setBooks(res.data.data || []);
    } catch (err) {
      console.error(err);
      setError('Error cargando libros');
    } finally {
      setLoading(false);
    }
  }

  async function handleDelete(id) {
    if (!confirm('¿Eliminar este libro?')) return;
    try {
      await api.delete(`/books/${id}`);
      fetchBooks();
    } catch (err) {
      alert('Error eliminando: ' + (err?.response?.data?.message || err.message));
    }
  }

  return (
    <div>
      <h2>Libros</h2>
      <div className="grid">
        <div className="card">
          <h3>{editing ? 'Editar libro' : 'Crear libro'}</h3>
          <BookForm
            editing={editing}
            onSaved={() => { setEditing(null); fetchBooks(); }}
            onCancel={() => setEditing(null)}
          />
        </div>
        <div className="card">
          <h3>Listado de libros</h3>
          {loading && <p>Cargando...</p>}
          {error && <p className="error">{error}</p>}
          <table className="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Género</th>
                <th>Disponibles</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {books.map(b => (
                <tr key={b.id}>
                  <td>{b.id}</td>
                  <td>{b.title}</td>
                  <td>{b.author?.name || ''}</td>
                  <td>{b.genre?.name || ''}</td>
                  <td>{b.available_copies}</td>
                  <td>
                    <button onClick={() => setEditing(b)}>Editar</button>
                    <button
                      style={{ background: '#ef4444', marginLeft: 8 }}
                      onClick={() => handleDelete(b.id)}
                    >
                      Eliminar
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
