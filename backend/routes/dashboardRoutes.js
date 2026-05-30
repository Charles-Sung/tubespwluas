const express = require('express');
const router = express.Router();
const dashboardController = require('../controllers/dashboardController');
const { authenticate } = require('../middleware/authMiddleware');

// Dashboard stats - accessible by any authenticated user
router.get('/', authenticate, dashboardController.getDashboardStats);

module.exports = router;
