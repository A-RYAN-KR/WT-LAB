import React, { useState, useEffect } from 'react';
import './App.css';

export default function Bookstore() {
  const [page, setPage] = useState('home'); // home, login, register, catalog
  const [user, setUser] = useState(null);
  const [books, setBooks] = useState([]);
  const [formData, setFormData] = useState({});

  useEffect(() => {
    if (page === 'catalog') {
      fetch('http://localhost/api.php?action=catalog')
        .then(res => res.json()).then(data => setBooks(data));
    }
  }, [page]);

  const handleAuth = async (action) => {
    const res = await fetch(`http://localhost/api.php?action=${action}`, {
      method: 'POST', body: JSON.stringify(formData)
    });
    const result = await res.json();
    if (result.success) {
      action === 'login' ? setPage('catalog') : setPage('login');
    } else { alert("Error in " + action); }
  };

  return (
    <div className="app">
      <nav className="navbar">
        <h2 onClick={() => setPage('home')}>VIT Books</h2>
        <div>
          <button onClick={() => setPage('home')}>Home</button>
          <button onClick={() => setPage('catalog')}>Catalogue</button>
          {!user && <button onClick={() => setPage('login')}>Login</button>}
        </div>
      </nav>

      <main className="content">
        {page === 'home' && (
          <div className="hero">
            <h1>Welcome to VIT Online Bookstore</h1>
            <p>Your one-stop shop for academic excellence.</p>
            <button className="cta" onClick={() => setPage('catalog')}>Browse Books</button>
          </div>
        )}

        {(page === 'login' || page === 'register') && (
          <div className="form-card">
            <h2>{page.toUpperCase()}</h2>
            {page === 'register' && <input placeholder="Name" onChange={e => setFormData({...formData, name: e.target.value})} />}
            <input placeholder="Email" onChange={e => setFormData({...formData, email: e.target.value})} />
            <input type="password" placeholder="Password" onChange={e => setFormData({...formData, pass: e.target.value})} />
            <button onClick={() => handleAuth(page)}>{page}</button>
            <p onClick={() => setPage(page === 'login' ? 'register' : 'login')}>
              {page === 'login' ? "New user? Register" : "Have account? Login"}
            </p>
          </div>
        )}

        {page === 'catalog' && (
          <div className="catalog-grid">
            {books.map(book => (
              <div key={book.id} className="book-card">
                <img src={book.image} alt={book.title} />
                <h3>{book.title}</h3>
                <p>{book.author}</p>
                <span className="price">₹{book.price}</span>
                <button>Add to Cart</button>
              </div>
            ))}
          </div>
        )}
      </main>
    </div>
  );
}