import React, { useEffect, useState } from 'react';
import api from '../api';

export default function Users(){
  const [users, setUsers] = useState([]);
  const [penalties, setPenalties] = useState([]); // 👈 penalizaciones
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchUsers();
    fetchPenalties(); // 👈 traer penalizaciones
  }, []);

  async function fetchUsers() {
    try {
      setLoading(true);
      const res = await api.get('/users');
      setUsers(res.data.data || []);
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

  async function handleCreate(e) {
    e.preventDefault();
    try {
      await api.post('/users', { name, email, phone });
      setName(''); setEmail(''); setPhone('');
      fetchUsers();
    } catch (err) {
      alert('Error: ' + (err?.response?.data?.message || err.message));
    }
  }

  // IDs de usuarios con penalización
  const penalizedUserIds = new Set(penalties.map(p => p.user?.id || p.user_id));

  return (
    <div>
      <h2>Usuarios</h2>
      <div className="grid">
        <div className="card">
          <h3>Crear usuario</h3>
          <form onSubmit={handleCreate}>
            <div className="form-row">
              <input className="input" placeholder="Nombre" value={name} onChange={e=>setName(e.target.value)} required />
              <input className="input" placeholder="Email" value={email} onChange={e=>setEmail(e.target.value)} required />
            </div>
            <div className="form-row">
              <input className="input" placeholder="Teléfono" value={phone} onChange={e=>setPhone(e.target.value)} />
            </div>
            <div style={{marginTop:8}}>
              <button className="button" type="submit">Crear</button>
            </div>
          </form>
        </div>

        <div className="card">
          <h3>Listado usuarios</h3>
          {loading && <p>Cargando...</p>}
          {error && <p className="error">{error}</p>}
          <table className="table">
            <thead>
              <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th></tr>
            </thead>
            <tbody>
              {users.map(u => (
                <tr
                  key={u.id}
                  style={{
                    backgroundColor: penalizedUserIds.has(u.id) ? '#f8d7da' : 'transparent', // rojo suave
                    color: penalizedUserIds.has(u.id) ? '#721c24' : 'inherit' // texto rojo
                  }}
                >
                  <td>{u.id}</td>
                  <td>{u.name}</td>
                  <td>{u.email}</td>
                  <td>{u.phone}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
