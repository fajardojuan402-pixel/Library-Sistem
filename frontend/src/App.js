import React, { useState } from 'react';
import Books from './components/Books';
import Users from './components/Users';
import Loans from './components/Loans';
import Stats from './components/Stats';

export default function App(){
  const [tab, setTab] = useState('books');
  return (
    <div className="app-container">
      <header>
        <h1>Biblioteca</h1>
        <nav>
          <button className={tab==='books'?'active':''} onClick={()=>setTab('books')}>Libros</button>
          <button className={tab==='users'?'active':''} onClick={()=>setTab('users')}>Usuarios</button>
          <button className={tab==='loans'?'active':''} onClick={()=>setTab('loans')}>Préstamos</button>
          <button className={tab==='stats'?'active':''} onClick={()=>setTab('stats')}>Estadísticas</button>
        </nav>
      </header>

      <main>
        {tab==='books' && <Books />}
        {tab==='users' && <Users />}
        {tab==='loans' && <Loans />}
        {tab==='stats' && <Stats />}
      </main>

      <footer>
        <small>Frontend React • Consume API Laravel</small>
      </footer>
    </div>
  );
}
