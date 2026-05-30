const express = require('express');
const router = express.Router();
const receiptController = require('../controllers/receiptController');
const { authenticate, isStafAdmin } = require('../middleware/authMiddleware');

// All receipts routes require authentication
router.use(authenticate);

// GET all receipt history logs - accessible by all
router.get('/', receiptController.getAllReceipts);

// GET pending/approved items for receipt - Staf Admin / Admin only
router.get('/pending', isStafAdmin, receiptController.getPendingReceiptItems);

// POST record new receipt - Staf Admin / Admin only
router.post('/', isStafAdmin, receiptController.createReceipt);

module.exports = router;
