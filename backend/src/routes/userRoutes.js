const express = require('express');
const router = express.Router();
const UserController = require('../controllers/userController');
const authMiddleware = require('../middleware/authMiddleware');

// POST /api/users/register - Register new user
router.post('/register', UserController.register);

// POST /api/users/login - Login user
router.post('/login', UserController.login);

// GET /api/users/profile - Get user profile (protected)
router.get('/profile', authMiddleware, UserController.getProfile);

module.exports = router;
