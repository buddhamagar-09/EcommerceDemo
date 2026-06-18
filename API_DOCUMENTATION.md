# API Documentation

## Base URL
```
http://localhost:5000/api
```

## Authentication
Most endpoints require JWT authentication. Include the token in the Authorization header:
```
Authorization: Bearer <your_jwt_token>
```

---

## Endpoints

### Health Check
**GET** `/health`

Check if the API is running.

**Response:**
```json
{
  "status": "OK",
  "message": "Ecommerce API is running"
}
```

---

### Products

#### Get All Products
**GET** `/products`

Get all products with optional filtering and sorting.

**Query Parameters:**
- `search` (optional): Search products by name or description
- `category` (optional): Filter by category
- `minPrice` (optional): Minimum price filter
- `maxPrice` (optional): Maximum price filter
- `sortBy` (optional): Sort results (`price_asc`, `price_desc`, `name`, `rating`)

**Response:**
```json
{
  "success": true,
  "count": 6,
  "data": [
    {
      "id": "1",
      "name": "Wireless Headphones",
      "description": "High-quality wireless headphones with noise cancellation",
      "price": 99.99,
      "category": "Electronics",
      "image": "https://via.placeholder.com/300x300?text=Headphones",
      "stock": 50,
      "rating": 4.5,
      "reviews": 120
    }
  ]
}
```

#### Get Product by ID
**GET** `/products/:id`

Get a specific product by ID.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "1",
    "name": "Wireless Headphones",
    "description": "High-quality wireless headphones with noise cancellation",
    "price": 99.99,
    "category": "Electronics",
    "image": "https://via.placeholder.com/300x300?text=Headphones",
    "stock": 50,
    "rating": 4.5,
    "reviews": 120
  }
}
```

#### Get Categories
**GET** `/products/categories`

Get all product categories.

**Response:**
```json
{
  "success": true,
  "data": ["Electronics", "Accessories"]
}
```

---

### Users

#### Register
**POST** `/users/register`

Register a new user.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "securePassword123"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": "uuid",
      "email": "john@example.com",
      "name": "John Doe",
      "createdAt": "2026-02-16T06:00:00.000Z"
    },
    "token": "jwt_token_here"
  }
}
```

#### Login
**POST** `/users/login`

Login an existing user.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "securePassword123"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": "uuid",
      "email": "john@example.com",
      "name": "John Doe",
      "createdAt": "2026-02-16T06:00:00.000Z"
    },
    "token": "jwt_token_here"
  }
}
```

#### Get Profile
**GET** `/users/profile` 🔒

Get the authenticated user's profile.

**Headers:**
```
Authorization: Bearer <token>
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "email": "john@example.com",
    "name": "John Doe",
    "createdAt": "2026-02-16T06:00:00.000Z"
  }
}
```

---

### Cart

All cart endpoints require authentication 🔒

#### Get Cart
**GET** `/cart`

Get the user's shopping cart.

**Response:**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "productId": "1",
        "quantity": 2,
        "product": {
          "id": "1",
          "name": "Wireless Headphones",
          "price": 99.99,
          ...
        }
      }
    ],
    "total": 199.98
  }
}
```

#### Add to Cart
**POST** `/cart`

Add an item to the cart.

**Request Body:**
```json
{
  "productId": "1",
  "quantity": 2
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "productId": "1",
        "quantity": 2
      }
    ]
  }
}
```

#### Update Cart Item
**PUT** `/cart`

Update the quantity of a cart item.

**Request Body:**
```json
{
  "productId": "1",
  "quantity": 3
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "productId": "1",
        "quantity": 3
      }
    ]
  }
}
```

#### Remove from Cart
**DELETE** `/cart/:productId`

Remove an item from the cart.

**Response:**
```json
{
  "success": true,
  "data": {
    "items": []
  }
}
```

#### Clear Cart
**DELETE** `/cart`

Clear all items from the cart.

**Response:**
```json
{
  "success": true,
  "data": {
    "items": []
  }
}
```

---

### Orders

All order endpoints require authentication 🔒

#### Create Order
**POST** `/orders`

Create a new order from cart items.

**Request Body:**
```json
{
  "shippingAddress": {
    "fullName": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "address": "123 Main St",
    "city": "New York",
    "zipCode": "10001"
  },
  "paymentMethod": "card"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "order_uuid",
    "userId": "user_uuid",
    "items": [
      {
        "productId": "1",
        "name": "Wireless Headphones",
        "price": 99.99,
        "quantity": 2,
        "subtotal": 199.98
      }
    ],
    "total": 199.98,
    "shippingAddress": { ... },
    "paymentMethod": "card",
    "status": "pending",
    "createdAt": "2026-02-16T06:00:00.000Z"
  }
}
```

#### Get User Orders
**GET** `/orders`

Get all orders for the authenticated user.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "order_uuid",
      "userId": "user_uuid",
      "items": [...],
      "total": 199.98,
      "status": "pending",
      "createdAt": "2026-02-16T06:00:00.000Z"
    }
  ]
}
```

#### Get Order by ID
**GET** `/orders/:id`

Get a specific order by ID.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "order_uuid",
    "userId": "user_uuid",
    "items": [...],
    "total": 199.98,
    "shippingAddress": { ... },
    "paymentMethod": "card",
    "status": "pending",
    "createdAt": "2026-02-16T06:00:00.000Z"
  }
}
```

---

## Error Responses

All endpoints may return error responses in the following format:

```json
{
  "error": "Error message here"
}
```

Common HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `500` - Internal Server Error
