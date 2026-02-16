// In-memory product database
const products = [
  {
    id: '1',
    name: 'Wireless Headphones',
    description: 'High-quality wireless headphones with noise cancellation',
    price: 99.99,
    category: 'Electronics',
    image: 'https://via.placeholder.com/300x300?text=Headphones',
    stock: 50,
    rating: 4.5,
    reviews: 120
  },
  {
    id: '2',
    name: 'Smart Watch',
    description: 'Fitness tracking smart watch with heart rate monitor',
    price: 199.99,
    category: 'Electronics',
    image: 'https://via.placeholder.com/300x300?text=Smart+Watch',
    stock: 30,
    rating: 4.3,
    reviews: 85
  },
  {
    id: '3',
    name: 'Laptop Backpack',
    description: 'Durable laptop backpack with multiple compartments',
    price: 49.99,
    category: 'Accessories',
    image: 'https://via.placeholder.com/300x300?text=Backpack',
    stock: 100,
    rating: 4.7,
    reviews: 200
  },
  {
    id: '4',
    name: 'USB-C Hub',
    description: 'Multi-port USB-C hub with HDMI and card reader',
    price: 39.99,
    category: 'Electronics',
    image: 'https://via.placeholder.com/300x300?text=USB-C+Hub',
    stock: 75,
    rating: 4.4,
    reviews: 150
  },
  {
    id: '5',
    name: 'Wireless Mouse',
    description: 'Ergonomic wireless mouse with precision tracking',
    price: 29.99,
    category: 'Electronics',
    image: 'https://via.placeholder.com/300x300?text=Mouse',
    stock: 120,
    rating: 4.6,
    reviews: 180
  },
  {
    id: '6',
    name: 'Mechanical Keyboard',
    description: 'RGB mechanical keyboard with custom switches',
    price: 129.99,
    category: 'Electronics',
    image: 'https://via.placeholder.com/300x300?text=Keyboard',
    stock: 45,
    rating: 4.8,
    reviews: 95
  }
];

class ProductModel {
  static getAll(filters = {}) {
    let filteredProducts = [...products];

    // Filter by category
    if (filters.category) {
      filteredProducts = filteredProducts.filter(
        p => p.category.toLowerCase() === filters.category.toLowerCase()
      );
    }

    // Filter by search query
    if (filters.search) {
      const searchLower = filters.search.toLowerCase();
      filteredProducts = filteredProducts.filter(
        p => p.name.toLowerCase().includes(searchLower) ||
             p.description.toLowerCase().includes(searchLower)
      );
    }

    // Filter by price range
    if (filters.minPrice) {
      filteredProducts = filteredProducts.filter(p => p.price >= parseFloat(filters.minPrice));
    }
    if (filters.maxPrice) {
      filteredProducts = filteredProducts.filter(p => p.price <= parseFloat(filters.maxPrice));
    }

    // Sort
    if (filters.sortBy) {
      switch (filters.sortBy) {
        case 'price_asc':
          filteredProducts.sort((a, b) => a.price - b.price);
          break;
        case 'price_desc':
          filteredProducts.sort((a, b) => b.price - a.price);
          break;
        case 'name':
          filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
          break;
        case 'rating':
          filteredProducts.sort((a, b) => b.rating - a.rating);
          break;
      }
    }

    return filteredProducts;
  }

  static getById(id) {
    return products.find(p => p.id === id);
  }

  static getCategories() {
    const categories = [...new Set(products.map(p => p.category))];
    return categories;
  }
}

module.exports = ProductModel;
