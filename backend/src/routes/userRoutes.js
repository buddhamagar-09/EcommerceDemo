const express = require('express');
const router = express.Router();
const UserController = require('../controllers/userController');
const authMiddleware = require('../middleware/authMiddleware');
const { authLimiter, protectedLimiter } = require('../middleware/rateLimiter');

// POST /api/users/register - Register new user
router.post('/register', authLimiter, UserController.register);

// POST /api/users/login - Login user
router.post('/login', authLimiter, UserController.login);

// GET /api/users/profile - Get user profile (protected)
router.get('/profile', protectedLimiter, authMiddleware, UserController.getProfile);

module.exports = router;
