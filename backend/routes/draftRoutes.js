const express = require('express');
const router = express.Router();
const draftController = require('../controllers/draftController');
const { authenticate, authorize } = require('../middleware/authMiddleware');

// Hanya Kepala Laboratorium
router.use(authenticate, authorize(['Kepala Laboratorium']));

router.post('/', draftController.createDraft);
router.get('/', draftController.getDrafts);
router.put('/:id/submit', draftController.submitDraft);

module.exports = router;
