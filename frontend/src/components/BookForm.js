import React, { useEffect, useState } from 'react';
import api from '../api';

export default function BookForm({ editing, onSaved, onCancel }){
  const [title,setTitle]=useState('');
  const [authorId,setAuthorId]=useState('');
  const [genreId,setGenreId]=useState('');
  const [isbn,setIsbn]=useState('');
  const [total,setTotal]=useState(1);
  const [authors,setAuthors]=useState([]);
  const [genres,setGenres]=useState([]);
  const [error,setError]=useState(null);
  const [loading,setLoading]=useState(false);

  useEffect(()=>{ fetchMeta(); if(editing) fill(editing); else reset(); },[editing]);

  function fill(b){
    setTitle(b.title||''); setAuthorId(b.author_id||''); setGenreId(b.genre_id||''); setIsbn(b.isbn||''); setTotal(b.total_copies||1);
  }
  function reset(){ setTitle(''); setAuthorId(''); setGenreId(''); setIsbn(''); setTotal(1); setError(null); }

  async function fetchMeta(){
    try{
      const [a,g] = await Promise.all([api.get('/authors'), api.get('/genres')]);
      setAuthors(a.data.data||[]); setGenres(g.data.data||[]);
    }catch(e){ console.error(e); }
  }

  async function handleSubmit(e){
    e.preventDefault();
    setLoading(true); setError(null);
    const payload = { title, author_id: authorId, genre_id: genreId, isbn, total_copies: Number(total) };
    try{
      if(editing) await api.put('/books/'+editing.id, payload);
      else await api.post('/books', payload);
      reset(); if(onSaved) onSaved();
    }catch(err){
      setError(err?.response?.data?.message || err.message);
    }finally{ setLoading(false); }
  }

  return (
    <form onSubmit={handleSubmit}>
      <div className="form-row">
        <input className="input" placeholder="Título" value={title} onChange={e=>setTitle(e.target.value)} required />
        <select className="input" value={authorId} onChange={e=>setAuthorId(e.target.value)} required>
          <option value="">Selecciona autor</option>
          {authors.map(a=> <option key={a.id} value={a.id}>{a.name}</option>)}
        </select>
      </div>
      <div className="form-row">
        <select className="input" value={genreId} onChange={e=>setGenreId(e.target.value)} required>
          <option value="">Selecciona género</option>
          {genres.map(g=> <option key={g.id} value={g.id}>{g.name}</option>)}
        </select>
        <input className="input" placeholder="ISBN" value={isbn} onChange={e=>setIsbn(e.target.value)} required />
      </div>
      <div className="form-row">
        <input type="number" min="1" className="input" value={total} onChange={e=>setTotal(e.target.value)} required />
        <div style={{flex:1}}></div>
      </div>
      {error && <div className="error">{error}</div>}
      <div style={{marginTop:8}}>
        <button className="button" type="submit" disabled={loading}>{loading? 'Guardando...': (editing? 'Actualizar':'Crear')}</button>
        {editing && <button type="button" style={{marginLeft:8}} onClick={onCancel}>Cancelar</button>}
      </div>
    </form>

  );
}
