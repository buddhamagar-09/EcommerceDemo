import React from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { useCart } from '../contexts/CartContext';
import './Navbar.css';

const Navbar = () => {
  const { isAuthenticated, user, logout } = useAuth();
  const { cartCount } = useCart();

  return (
    <nav className="navbar">
      <div className="navbar-container">
        <Link to="/" className="navbar-brand">
          🛒 Ecommerce Demo
        </Link>
        <ul className="navbar-menu">
          <li>
            <Link to="/" className="navbar-link">
              Products
            </Link>
          </li>
          {isAuthenticated ? (
            <>
              <li className="navbar-cart">
                <Link to="/cart" className="navbar-link">
                  Cart
                  {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
                </Link>
              </li>
              <li>
                <Link to="/orders" className="navbar-link">
                  Orders
                </Link>
              </li>
              <li>
                <span className="navbar-link">Hello, {user?.name}</span>
              </li>
              <li>
                <button onClick={logout} className="navbar-button logout">
                  Logout
                </button>
              </li>
            </>
          ) : (
            <>
              <li>
                <Link to="/login">
                  <button className="navbar-button">Login</button>
                </Link>
              </li>
              <li>
                <Link to="/register">
                  <button className="navbar-button">Register</button>
                </Link>
              </li>
            </>
          )}
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
