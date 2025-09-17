import React, { useEffect, useState } from 'react';
import api from '../api';

export default function Stats() {
  const [availability, setAvailability] = useState(null);
  const [top, setTop] = useState([]);
  const [penalties, setPenalties] = useState({ totalAmount: 0, penalizedUsers: [] });
  const [error, setError] = useState(null);

  useEffect(() => { fetchStats(); }, []);

  async function fetchStats() {
    try {
      const [a, t, p] = await Promise.all([
        api.get('/stats/availability'),
        api.get('/stats/top-books'),
        api.get('/stats/penalties') // nueva ruta
      ]);

      setAvailability(a.data.data);
      setTop(t.data.data || []);
      setPenalties(p.data.data || { totalAmount: 0, penalizedUsers: [] });
    } catch (err) {
      setError(err.message || 'Error');
    }
  }

  return (
    <div>
      <h2>Estadísticas</h2>
      {error && <p className="error">{error}</p>}

      {availability && (
        <div className="card">
          <h3>Disponibilidad</h3>
          <p>Total de copias: <strong>{availability.totalCopies}</strong></p>
          <p>Copias disponibles: <strong>{availability.availableCopies}</strong></p>
          <p>Porcentaje disponibles: <strong>{Number(availability.percentAvailable).toFixed(2)}%</strong></p>
        </div>
      )}

      <div className="card" style={{ marginTop: 12 }}>
        <h3>Top libros por préstamos</h3>
        <ol>
          {top.length ? top.map(tb => (
            <li key={tb.bookId}>{tb.title} — {tb.loansCount} préstamos</li>
          )) : <li>No hay datos</li>}
        </ol>
      </div>

      <div className="card" style={{ marginTop: 12 }}>
        <h3>Penalizaciones</h3>
        <p>Total ganado por penalizaciones: <strong>${penalties.totalAmount.toLocaleString()}</strong></p>
        {penalties.penalizedUsers.length > 0 ? (
          <div>
            <p>Usuarios penalizados (no pueden prestar):</p>
            <ul>
              {penalties.penalizedUsers.map(u => (
                <li key={u.id}>{u.name} ({u.email})</li>
              ))}
            </ul>
          </div>
        ) : <p>No hay usuarios penalizados</p>}
      </div>
    </div>
  );
}

