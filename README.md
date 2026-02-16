# Ecommerce Demo

A full-stack ecommerce website built for practice, featuring a modern React frontend and Node.js/Express backend.

## 🚀 Features

- **User Authentication**: Register, login, and secure JWT-based authentication
- **Product Catalog**: Browse products with search, filtering, and sorting capabilities
- **Shopping Cart**: Add, update, and remove items from cart
- **Checkout Process**: Complete order flow with shipping information
- **Order Management**: View order history and order details
- **Responsive Design**: Works seamlessly on desktop and mobile devices

## 📁 Project Structure

```
EcommerceDemo/
├── backend/              # Node.js/Express API
│   ├── src/
│   │   ├── controllers/  # Request handlers
│   │   ├── models/       # Data models
│   │   ├── routes/       # API routes
│   │   ├── middleware/   # Auth & other middleware
│   │   └── server.js     # Express server setup
│   ├── package.json
│   └── .env.example
│
├── frontend/             # React application
│   ├── public/
│   ├── src/
│   │   ├── components/   # Reusable components
│   │   ├── pages/        # Page components
│   │   ├── contexts/     # React contexts (Auth, Cart)
│   │   ├── services/     # API service
│   │   ├── styles/       # CSS files
│   │   ├── App.js        # Main app component
│   │   └── index.js      # Entry point
│   ├── package.json
│   └── .env.example
│
└── README.md
```

## 🛠️ Technology Stack

### Backend
- **Node.js** & **Express**: Server and API framework
- **JWT**: Authentication tokens
- **bcryptjs**: Password hashing
- **CORS**: Cross-origin resource sharing
- **dotenv**: Environment configuration

### Frontend
- **React 18**: UI library
- **React Router**: Client-side routing
- **Axios**: HTTP client
- **Context API**: State management

## 📦 Installation & Setup

### Prerequisites
- Node.js (v14 or higher)
- npm or yarn

### Backend Setup

1. Navigate to the backend directory:
```bash
cd backend
```

2. Install dependencies:
```bash
npm install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Configure your `.env` file:
```env
PORT=5000
NODE_ENV=development
JWT_SECRET=your_jwt_secret_key_here_change_in_production
```

5. Start the backend server:
```bash
# Development mode with auto-reload
npm run dev

# Production mode
npm start
```

The backend API will be available at `http://localhost:5000`

### Frontend Setup

1. Navigate to the frontend directory:
```bash
cd frontend
```

2. Install dependencies:
```bash
npm install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Configure your `.env` file:
```env
REACT_APP_API_URL=http://localhost:5000/api
```

5. Start the React development server:
```bash
npm start
```

The frontend will be available at `http://localhost:3000`

## 🔑 API Endpoints

### Products
- `GET /api/products` - Get all products (with optional filters)
- `GET /api/products/:id` - Get product by ID
- `GET /api/products/categories` - Get all categories

### Users
- `POST /api/users/register` - Register new user
- `POST /api/users/login` - Login user
- `GET /api/users/profile` - Get user profile (protected)

### Cart
- `GET /api/cart` - Get user's cart (protected)
- `POST /api/cart` - Add item to cart (protected)
- `PUT /api/cart` - Update cart item (protected)
- `DELETE /api/cart/:productId` - Remove item from cart (protected)
- `DELETE /api/cart` - Clear cart (protected)

### Orders
- `POST /api/orders` - Create new order (protected)
- `GET /api/orders` - Get user's orders (protected)
- `GET /api/orders/:id` - Get specific order (protected)

## 📱 Usage

1. **Browse Products**: Visit the homepage to see all available products
2. **Register/Login**: Create an account or login to access cart and checkout
3. **Add to Cart**: Click "Add to Cart" on any product
4. **View Cart**: Click on the cart icon in the navbar
5. **Checkout**: Proceed to checkout and fill in shipping information
6. **View Orders**: Check your order history in the Orders page

## 🔒 Security Features

- Password hashing with bcrypt
- JWT-based authentication
- Protected API routes
- Input validation
- CORS configuration

## 🎨 Key Components

### Frontend Components
- **Navbar**: Navigation with cart counter and auth status
- **ProductCard**: Displays product information and actions
- **ProductList**: Grid of products with filtering
- **Cart**: Shopping cart management
- **Checkout**: Order placement form
- **Orders**: Order history display

### Backend Models
- **ProductModel**: Product data and filtering logic
- **UserModel**: User authentication and management
- **CartModel**: Shopping cart operations
- **OrderModel**: Order creation and retrieval

## 🚧 Development Notes

- Currently using in-memory storage (arrays) for data
- For production, integrate a real database (MongoDB, PostgreSQL, etc.)
- Payment integration is simulated (integrate Stripe/PayPal for real payments)
- Add more features like: product reviews, wishlists, admin panel, etc.

## 📝 Future Enhancements

- [ ] Database integration (MongoDB/PostgreSQL)
- [ ] Real payment gateway integration
- [ ] Product reviews and ratings
- [ ] Admin dashboard for product management
- [ ] Wishlist functionality
- [ ] Email notifications
- [ ] Image upload for products
- [ ] Advanced filtering and search
- [ ] Order tracking
- [ ] User profile management

## 🤝 Contributing

This is a practice project. Feel free to fork and modify as needed for your learning purposes.

## 📄 License

MIT License - Feel free to use this project for learning and practice.

---

**Note**: This is a demo project for educational purposes. Do not use in production without proper security measures, database implementation, and thorough testing.