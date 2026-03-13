const express = require('express');
const router = express.Router();
const CartController = require('../controllers/cartController');
const authMiddleware = require('../middleware/authMiddleware');
const { protectedLimiter } = require('../middleware/rateLimiter');

// All cart routes require authentication and rate limiting
router.use(protectedLimiter);
router.use(authMiddleware);

// GET /api/cart - Get user's cart
router.get('/', CartController.getCart);

// POST /api/cart - Add item to cart
router.post('/', CartController.addItem);

// PUT /api/cart - Update cart item quantity
router.put('/', CartController.updateItem);

// DELETE /api/cart/:productId - Remove item from cart
router.delete('/:productId', CartController.removeItem);

// DELETE /api/cart - Clear entire cart
router.delete('/', CartController.clearCart);

module.exports = router;
