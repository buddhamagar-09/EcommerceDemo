const express = require('express');
const router = express.Router();
const OrderController = require('../controllers/orderController');
const authMiddleware = require('../middleware/authMiddleware');

// All order routes require authentication
router.use(authMiddleware);

// POST /api/orders - Create new order
router.post('/', OrderController.createOrder);

// GET /api/orders - Get user's orders
router.get('/', OrderController.getOrders);

// GET /api/orders/:id - Get specific order
router.get('/:id', OrderController.getOrderById);

module.exports = router;
