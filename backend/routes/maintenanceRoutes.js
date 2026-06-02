const express = require('express');
const router = express.Router();
const maintenanceController = require('../controllers/maintenanceController');
const { authenticate } = require('../middleware/authMiddleware');

router.get('/', authenticate, maintenanceController.getAllMaintenance);
router.post('/', authenticate, maintenanceController.createMaintenance);

module.exports = router;
