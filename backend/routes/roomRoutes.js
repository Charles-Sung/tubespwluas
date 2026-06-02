const express = require('express');
const router = express.Router();
const roomController = require('../controllers/roomController');
const { authenticate, isAdmin } = require('../middleware/authMiddleware');

// All room routes require authentication
router.use(authenticate);

// GET is accessible by any logged in user
router.get('/', roomController.getAllRooms);
router.get('/:id', roomController.getRoomById);

// Creation, update and deletion requires Admin role
router.post('/', isAdmin, roomController.createRoom);
router.put('/:id', isAdmin, roomController.updateRoom);
router.delete('/:id', isAdmin, roomController.deleteRoom);

module.exports = router;
