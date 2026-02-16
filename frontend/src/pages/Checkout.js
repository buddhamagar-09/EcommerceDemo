import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useCart } from '../contexts/CartContext';
import api from '../services/api';
import './Checkout.css';

const Checkout = () => {
  const navigate = useNavigate();
  const { cartItems, cartTotal, clearCart } = useCart();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [formData, setFormData] = useState({
    fullName: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    zipCode: '',
    paymentMethod: 'card'
  });

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const orderData = {
        shippingAddress: {
          fullName: formData.fullName,
          email: formData.email,
          phone: formData.phone,
          address: formData.address,
          city: formData.city,
          zipCode: formData.zipCode
        },
        paymentMethod: formData.paymentMethod
      };

      const response = await api.post('/orders', orderData);
      const order = response.data.data;
      
      alert('Order placed successfully!');
      await clearCart();
      navigate(`/orders/${order.id}`);
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to place order');
    } finally {
      setLoading(false);
    }
  };

  if (cartItems.length === 0) {
    navigate('/cart');
    return null;
  }

  return (
    <div className="checkout-page">
      <h1 className="checkout-title">Checkout</h1>
      {error && <div className="auth-error">{error}</div>}
      
      <form onSubmit={handleSubmit} className="checkout-form">
        <div className="checkout-section">
          <h3>Shipping Information</h3>
          <div className="form-group">
            <label>Full Name</label>
            <input
              type="text"
              name="fullName"
              value={formData.fullName}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-row">
            <div className="form-group">
              <label>Email</label>
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                required
              />
            </div>
            <div className="form-group">
              <label>Phone</label>
              <input
                type="tel"
                name="phone"
                value={formData.phone}
                onChange={handleChange}
                required
              />
            </div>
          </div>
          <div className="form-group">
            <label>Address</label>
            <input
              type="text"
              name="address"
              value={formData.address}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-row">
            <div className="form-group">
              <label>City</label>
              <input
                type="text"
                name="city"
                value={formData.city}
                onChange={handleChange}
                required
              />
            </div>
            <div className="form-group">
              <label>Zip Code</label>
              <input
                type="text"
                name="zipCode"
                value={formData.zipCode}
                onChange={handleChange}
                required
              />
            </div>
          </div>
        </div>

        <div className="checkout-section">
          <h3>Payment Method</h3>
          <div className="form-group">
            <select
              name="paymentMethod"
              value={formData.paymentMethod}
              onChange={handleChange}
              required
            >
              <option value="card">Credit/Debit Card</option>
              <option value="paypal">PayPal</option>
              <option value="cod">Cash on Delivery</option>
            </select>
          </div>
        </div>

        <div className="order-summary-box">
          <h3>Order Summary</h3>
          {cartItems.map((item) => (
            item.product && (
              <div key={item.productId} className="order-item">
                <span>
                  {item.product.name} x {item.quantity}
                </span>
                <span>${(item.product.price * item.quantity).toFixed(2)}</span>
              </div>
            )
          ))}
          <div className="order-item" style={{ fontWeight: 'bold', marginTop: '1rem', paddingTop: '1rem', borderTop: '2px solid #ddd' }}>
            <span>Total:</span>
            <span>${cartTotal.toFixed(2)}</span>
          </div>
        </div>

        <button
          type="submit"
          className="place-order-btn"
          disabled={loading}
        >
          {loading ? 'Placing Order...' : 'Place Order'}
        </button>
      </form>
    </div>
  );
};

export default Checkout;
