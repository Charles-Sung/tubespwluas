const express = require('express');
const router = express.Router();
const itemController = require('../controllers/itemController');
const { authenticate, isAdmin } = require('../middleware/authMiddleware');

// All item routes require authentication
router.use(authenticate);

// GET is accessible by any logged in user
router.get('/', itemController.getAllItems);
router.get('/:id', itemController.getItemById);

// Creation, update and deletion requires Admin role
router.post('/', isAdmin, itemController.createItem);
router.put('/:id', isAdmin, itemController.updateItem);
router.delete('/:id', isAdmin, itemController.deleteItem);

module.exports = router;
