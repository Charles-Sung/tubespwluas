const express = require('express');
const router = express.Router();
const draftController = require('../controllers/draftController');
const { authenticate, isKalab, isKaprodi } = require('../middleware/authMiddleware');

// All procurement routes require authentication
router.use(authenticate);

// GET all drafts - accessible by all roles
router.get('/', draftController.getAllDrafts);
router.get('/:id', draftController.getDraftById);

// Create draft - Kalab and Admin only
router.post('/', isKalab, draftController.createDraft);

// Update draft - Kalab and Admin only
router.put('/:id', isKalab, draftController.updateDraft);

// Submit draft to Kaprodi - Kalab and Admin only
router.put('/:id/submit', isKalab, draftController.submitDraft);

// Review individual items - Kaprodi and Admin only
router.put('/detail/:detailId/review', isKaprodi, draftController.reviewItem);

// Finalize/lock draft - Kaprodi and Admin only
router.put('/:id/finalize', isKaprodi, draftController.finalizeDraft);

module.exports = router;
