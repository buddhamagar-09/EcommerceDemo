const express = require('express');
const router = express.Router();
const ProductController = require('../controllers/productController');

// GET /api/products - Get all products with optional filters
router.get('/', ProductController.getAll);

// GET /api/products/categories - Get all categories
router.get('/categories', ProductController.getCategories);

// GET /api/products/:id - Get product by ID
router.get('/:id', ProductController.getById);

module.exports = router;
