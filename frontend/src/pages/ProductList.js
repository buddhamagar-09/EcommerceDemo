import React, { useState, useEffect } from 'react';
import ProductCard from '../components/ProductCard';
import api from '../services/api';
import './ProductList.css';

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filters, setFilters] = useState({
    search: '',
    category: '',
    sortBy: ''
  });

  useEffect(() => {
    fetchProducts();
  }, [filters]);

  const fetchProducts = async () => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (filters.search) params.append('search', filters.search);
      if (filters.category) params.append('category', filters.category);
      if (filters.sortBy) params.append('sortBy', filters.sortBy);

      const response = await api.get(`/products?${params.toString()}`);
      setProducts(response.data.data);
      setError(null);
    } catch (err) {
      setError('Failed to load products');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    setFilters(prev => ({ ...prev, [name]: value }));
  };

  if (loading) return <div className="loading">Loading products...</div>;
  if (error) return <div className="error">{error}</div>;

  return (
    <div className="products-page">
      <div className="page-header">
        <h1 className="page-title">Our Products</h1>
        <div className="filters">
          <div className="filter-group">
            <label>Search</label>
            <input
              type="text"
              name="search"
              placeholder="Search products..."
              value={filters.search}
              onChange={handleFilterChange}
            />
          </div>
          <div className="filter-group">
            <label>Category</label>
            <select
              name="category"
              value={filters.category}
              onChange={handleFilterChange}
            >
              <option value="">All Categories</option>
              <option value="Electronics">Electronics</option>
              <option value="Accessories">Accessories</option>
            </select>
          </div>
          <div className="filter-group">
            <label>Sort By</label>
            <select
              name="sortBy"
              value={filters.sortBy}
              onChange={handleFilterChange}
            >
              <option value="">Default</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
              <option value="name">Name</option>
              <option value="rating">Rating</option>
            </select>
          </div>
        </div>
      </div>

      {products.length === 0 ? (
        <div className="no-products">
          <p>No products found</p>
        </div>
      ) : (
        <div className="products-grid">
          {products.map(product => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      )}
    </div>
  );
};

export default ProductList;
