const express = require('express');
const router = express.Router();
const bhpController = require('../controllers/bhpController');
const { authenticate } = require('../middleware/authMiddleware');

router.get('/', authenticate, bhpController.getAllBhp);
router.put('/', authenticate, bhpController.updateBhpStock);

module.exports = router;
