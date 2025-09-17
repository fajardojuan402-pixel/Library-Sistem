import React, { useEffect, useState } from 'react';
import api from '../api';

export default function Loans() {
  const [users, setUsers] = useState([]);
  const [books, setBooks] = useState([]);
  const [selectedUser, setSelectedUser] = useState('');
  const [selectedBook, setSelectedBook] = useState('');
  const [loans, setLoans] = useState([]);
  const [penalties, setPenalties] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchMeta();
    fetchLoans();
    fetchPenalties();
  }, []);

  async function fetchMeta() {
    try {
      const [u, b] = await Promise.all([api.get('/users'), api.get('/books')]);
      setUsers(u.data.data || []);
      setBooks(b.data.data || []);
    } catch (e) {
      console.error(e);
    }
  }

  async function fetchLoans() {
    try {
      setLoading(true);
      const res = await api.get('/loans');
      setLoans(res.data.data || []);
    } catch (e) {
      setError(e.message);
    } finally {
      setLoading(false);
    }
  }

  async function fetchPenalties() {
    try {
      const res = await api.get('/penalties');
      setPenalties(res.data.data || []);
    } catch (e) {
      console.error(e);
    }
  }

  async function handleLoan(e) {
    e.preventDefault();
    try {
      await api.post('/loans', { user_id: selectedUser, book_id: selectedBook });
      setSelectedBook('');
      setSelectedUser('');
      fetchLoans();
      fetchMeta();
    } catch (err) {
      alert('Error: ' + (err?.response?.data?.message || err.message));
    }
  }

  async function handleReturn(id) {
    if (!confirm('¿Marcar como devuelto?')) return;
    try {
      await api.put('/loans/' + id + '/return');
      fetchLoans();
      fetchMeta();
    } catch (err) {
      alert('Error: ' + (err?.response?.data?.message || err.message));
    }
  }

  async function handlePenalize(id) {
    if (!confirm('¿Enviar penalización al usuario?')) return;
    try {
      await api.post('/loans/' + id + '/penalize');
      fetchLoans();
      fetchPenalties();
    } catch (err) {
      alert('Error: ' + (err?.response?.data?.message || err.message));
    }
  }

  async function handleDeletePenalty(id) {
    if (!confirm('¿Seguro que deseas eliminar esta penalización?')) return;
    try {
      await api.delete(`/penalties/${id}`);
      fetchPenalties();
    } catch (err) {
      alert('Error: ' + (err?.response?.data?.message || err.message));
    }
  }

  const today = new Date();
  const activeLoans = loans.filter(l => !l.return_date);
  const returnedLoans = loans.filter(l => l.return_date);

  // IDs de usuarios penalizados
  const penalizedUserIds = new Set(penalties.map(p => p.user?.id || p.user_id));

  return (
    <div>
      <h2>Préstamos de libros</h2>

      {/* Nuevo préstamo */}
      <div className="card">
        <h3>Nuevo préstamo</h3>
        <form onSubmit={handleLoan}>
          <div className="form-row">
            <select
              className="input"
              value={selectedUser}
              onChange={e => setSelectedUser(e.target.value)}
              required
            >
              <option value="">Selecciona usuario</option>
              {users
                .filter(u => !penalizedUserIds.has(u.id)) // ❌ excluir penalizados
                .map(u => (
                  <option key={u.id} value={u.id}>
                    {u.name} ({u.email})
                  </option>
                ))
              }
            </select>

            <select
              className="input"
              value={selectedBook}
              onChange={e => setSelectedBook(e.target.value)}
              required
            >
              <option value="">Selecciona libro</option>
              {books.map(b => (
                <option key={b.id} value={b.id}>
                  {b.title} ({b.available_copies} disponibles)
                </option>
              ))}
            </select>
          </div>
          <div style={{ marginTop: 8 }}>
            <button className="button" type="submit">Prestar</button>
          </div>
        </form>
      </div>

      {/* Préstamos activos */}
      <div className="card">
        <h3>Préstamos activos</h3>
        {loading && <p>Cargando...</p>}
        {error && <p className="error">{error}</p>}
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Usuario</th>
              <th>Libro</th>
              <th>Fecha préstamo</th>
              <th>Fecha devolución</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {activeLoans.map(l => {
              const dueDate = new Date(l.due_date);
              const isLate = dueDate < today;
              return (
                <tr key={l.id}>
                  <td>{l.id}</td>
                  <td>{l.user?.name}</td>
                  <td>{l.book?.title}</td>
                  <td>{l.loan_date}</td>
                  <td>{l.due_date}</td>
                  <td style={{ color: isLate ? 'red' : 'green' }}>
                    {isLate ? 'Vencido' : 'Dentro del plazo'}
                  </td>
                  <td>
                    {!isLate && (
                      <button className="button" onClick={() => handleReturn(l.id)}>Devolver</button>
                    )}
                    {isLate && (
                      <button className="button red" onClick={() => handlePenalize(l.id)}>Penalizar</button>
                    )}
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {/* Préstamos devueltos */}
      <div className="card">
        <h3>Préstamos devueltos</h3>
        {returnedLoans.length === 0 && <p>No hay préstamos devueltos recientes.</p>}
        {returnedLoans.length > 0 && (
          <table className="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Fecha préstamo</th>
                <th>Fecha devolución</th>
              </tr>
            </thead>
            <tbody>
              {returnedLoans.map(l => (
                <tr key={l.id}>
                  <td>{l.id}</td>
                  <td>{l.user?.name}</td>
                  <td>{l.book?.title}</td>
                  <td>{l.loan_date}</td>
                  <td>{l.return_date}</td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>

      {/* Penalizaciones */}
      <div className="card">
        <h3>Penalizaciones</h3>
        {penalties.length === 0 && <p>No hay penalizaciones registradas.</p>}
        {penalties.length > 0 && (
          <table className="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Motivo</th>
                <th>Monto</th>
                <th>Fecha enviada</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {penalties.map(p => (
                <tr
                  key={p.id}
                  style={{
                    backgroundColor: '#f8d7da',
                    color: '#721c24'
                  }}
                >
                  <td>{p.id}</td>
                  <td>{p.user?.name || p.loan?.user?.name} ({p.user?.email || p.loan?.user?.email})</td>
                  <td>{p.loan?.book?.title}</td>
                  <td>{p.reason}</td>
                  <td>{p.amount ?? '-'}</td>
                  <td>{p.sent_at ?? 'No enviado'}</td>
                  <td>
                    <button className="button red" onClick={() => handleDeletePenalty(p.id)}>Eliminar</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
}
