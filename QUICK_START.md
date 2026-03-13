# Quick Start Guide

Get the ecommerce demo up and running in minutes!

## Prerequisites
- Node.js (v14 or higher)
- npm or yarn

## Installation

### 1. Clone the repository
```bash
git clone https://github.com/buddhamagar-09/EcommerceDemo.git
cd EcommerceDemo
```

### 2. Backend Setup
```bash
cd backend
npm install
cp .env.example .env
# Edit .env if needed (defaults work fine for development)
npm start
```
✅ Backend running at http://localhost:5000

### 3. Frontend Setup (in a new terminal)
```bash
cd frontend
npm install
cp .env.example .env
# Edit .env if needed (defaults work fine for development)
npm start
```
✅ Frontend running at http://localhost:3000

## Test It Out!

1. **Browse Products**: Open http://localhost:3000
2. **Register**: Click "Register" and create an account
3. **Shop**: Add products to your cart
4. **Checkout**: Complete an order
5. **View Orders**: Check your order history

## Sample Credentials
Since we start fresh, you'll need to register a new account. Use any email and password:
- Name: Test User
- Email: test@example.com
- Password: password123

## Quick API Test

Test the backend directly:

```bash
# Health check
curl http://localhost:5000/api/health

# Get products
curl http://localhost:5000/api/products

# Register user
curl -X POST http://localhost:5000/api/users/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123"}'
```

## Development Mode

### Backend with hot reload
```bash
cd backend
npm run dev
```

### Frontend with hot reload
```bash
cd frontend
npm start
```

## Available Products

The app comes with 6 sample products:
1. Wireless Headphones - $99.99
2. Smart Watch - $199.99
3. Laptop Backpack - $49.99
4. USB-C Hub - $39.99
5. Wireless Mouse - $29.99
6. Mechanical Keyboard - $129.99

## Features to Try

- ✅ Search products by name
- ✅ Filter by category
- ✅ Sort by price, name, or rating
- ✅ Add items to cart
- ✅ Update quantities
- ✅ Remove items
- ✅ Complete checkout
- ✅ View order history

## Troubleshooting

### Port already in use
If port 5000 or 3000 is busy:
- Backend: Change `PORT` in `backend/.env`
- Frontend: Change port when prompted or set `PORT` environment variable

### Cannot connect to backend
- Ensure backend is running on port 5000
- Check `REACT_APP_API_URL` in `frontend/.env`

### Cors errors
- Restart both frontend and backend
- Clear browser cache

## Next Steps

- Read [README.md](README.md) for detailed documentation
- Check [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for API details
- Review [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) for architecture

## Need Help?

This is a practice project! Feel free to:
- Modify and experiment
- Add new features
- Break things and learn
- Use as a template for your projects

Happy coding! 🚀
