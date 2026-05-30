const express = require('express');
const router = express.Router();
const inventoryController = require('../controllers/inventoryController');
const { authenticate } = require('../middleware/authMiddleware');

router.get('/', authenticate, inventoryController.getAllInventories);

module.exports = router;
