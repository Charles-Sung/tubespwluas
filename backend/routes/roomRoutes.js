const express = require('express');
const router = express.Router();
const roomController = require('../controllers/roomController');
const { authenticate, isAdmin } = require('../middleware/authMiddleware');

router.use(authenticate, isAdmin);

router.get('/', roomController.getAllRooms);
router.post('/', roomController.createRoom);
router.get('/:id', roomController.getRoomById);
router.put('/:id', roomController.updateRoom);
router.delete('/:id', roomController.deleteRoom);

module.exports = router;
