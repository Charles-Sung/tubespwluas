const express = require('express');
const router = express.Router();
const reviewController = require('../controllers/reviewController');
const { authenticate, authorize } = require('../middleware/authMiddleware');

// Hanya Kaprodi
router.use(authenticate, authorize(['Ketua Program Studi']));

router.get('/', reviewController.getSubmittedDrafts);
router.put('/detail/:id', reviewController.reviewDetail);
router.put('/:id/finalize', reviewController.finalizeDraft);

module.exports = router;
