const { sequelize, ProcurementDraft, ProcurementDetail, Item, User, Inventory } = require('../models');

// GET all drafts with details (and associated items)
const getAllDrafts = async (req, res) => {
    try {
        const { year } = req.query;
        const whereClause = year ? { year } : {};
        
        const drafts = await ProcurementDraft.findAll({
            where: whereClause,
            include: [
                {
                    model: User,
                    as: 'user',
                    attributes: ['id', 'name', 'email']
                },
                {
                    model: ProcurementDetail,
                    as: 'details',
                    include: [
                        {
                            model: Item,
                            as: 'item',
                            attributes: ['id', 'name', 'type']
                        },
                        {
                            model: Inventory,
                            as: 'replaced_inventory',
                            attributes: ['id', 'label_number', 'condition']
                        }
                    ]
                }
            ],
            order: [['created_at', 'DESC']]
        });
        return res.status(200).json(drafts);
    } catch (error) {
        console.error('Error fetching procurement drafts:', error);
        return res.status(500).json({ message: 'Gagal mengambil data draf pengadaan.' });
    }
};

// GET draft by ID
const getDraftById = async (req, res) => {
    try {
        const draft = await ProcurementDraft.findByPk(req.params.id, {
            include: [
                {
                    model: User,
                    as: 'user',
                    attributes: ['id', 'name', 'email']
                },
                {
                    model: ProcurementDetail,
                    as: 'details',
                    include: [
                        {
                            model: Item,
                            as: 'item',
                            attributes: ['id', 'name', 'type']
                        },
                        {
                            model: Inventory,
                            as: 'replaced_inventory',
                            attributes: ['id', 'label_number', 'condition']
                        }
                    ]
                }
            ]
        });

        if (!draft) {
            return res.status(404).json({ message: 'Draf pengadaan tidak ditemukan.' });
        }
        return res.status(200).json(draft);
    } catch (error) {
        console.error('Error fetching draft:', error);
        return res.status(500).json({ message: 'Gagal mengambil detail draf pengadaan.' });
    }
};

// POST create draft with items (Atomic Transaction)
const createDraft = async (req, res) => {
    const t = await sequelize.transaction();
    try {
        const { title, year, details } = req.body;
        const user_id = req.user.id; // From JWT authentication

        if (!title || !year || !details || !Array.isArray(details) || details.length === 0) {
            return res.status(400).json({ message: 'Title, year, and details list are required.' });
        }

        // Create the draft header
        const draft = await ProcurementDraft.create({
            user_id,
            title,
            year,
            status: 'draft'
        }, { transaction: t });

        // Prepare detail items
        const detailRecords = details.map(item => ({
            procurement_draft_id: draft.id,
            item_id: item.item_id,
            quantity: item.quantity,
            price: item.price,
            purchase_link: item.purchase_link || null,
            replaced_inventory_id: item.replaced_inventory_id || null,
            status: 'pending'
        }));

        // Bulk create detail items
        await ProcurementDetail.bulkCreate(detailRecords, { transaction: t });

        await t.commit();

        // Fetch fully populated draft to return
        const fullDraft = await ProcurementDraft.findByPk(draft.id, {
            include: [{ model: ProcurementDetail, as: 'details' }]
        });

        return res.status(201).json({
            message: 'Draf pengadaan berhasil dibuat.',
            data: fullDraft
        });
    } catch (error) {
        await t.rollback();
        console.error('Error creating procurement draft:', error);
        return res.status(500).json({ message: 'Gagal membuat draf pengadaan.' });
    }
};

// PUT update draft with items (Atomic Transaction)
const updateDraft = async (req, res) => {
    const t = await sequelize.transaction();
    try {
        const { title, year, details } = req.body;
        
        const draft = await ProcurementDraft.findByPk(req.params.id);
        if (!draft) {
            await t.rollback();
            return res.status(404).json({ message: 'Draf pengadaan tidak ditemukan.' });
        }
        
        if (draft.status !== 'draft') {
            await t.rollback();
            return res.status(400).json({ message: 'Hanya draf baru yang bisa diedit.' });
        }

        if (title) draft.title = title;
        if (year) draft.year = year;
        await draft.save({ transaction: t });

        if (details && Array.isArray(details)) {
            // Delete old details
            await ProcurementDetail.destroy({
                where: { procurement_draft_id: draft.id },
                transaction: t
            });

            // Re-create new details
            const detailRecords = details.map(item => ({
                procurement_draft_id: draft.id,
                item_id: item.item_id,
                quantity: item.quantity,
                price: item.price,
                purchase_link: item.purchase_link || null,
                replaced_inventory_id: item.replaced_inventory_id || null,
                status: 'pending'
            }));

            await ProcurementDetail.bulkCreate(detailRecords, { transaction: t });
        }

        await t.commit();

        const fullDraft = await ProcurementDraft.findByPk(draft.id, {
            include: [{ model: ProcurementDetail, as: 'details' }]
        });

        return res.status(200).json({
            message: 'Draf pengadaan berhasil diperbarui.',
            data: fullDraft
        });
    } catch (error) {
        await t.rollback();
        console.error('Error updating procurement draft:', error);
        return res.status(500).json({ message: 'Gagal memperbarui draf pengadaan.' });
    }
};

// PUT submit draft (Change status to submitted)
const submitDraft = async (req, res) => {
    try {
        const draft = await ProcurementDraft.findByPk(req.params.id);
        if (!draft) {
            return res.status(404).json({ message: 'Draf pengadaan tidak ditemukan.' });
        }

        if (draft.status !== 'draft') {
            return res.status(400).json({ message: 'Hanya draf baru yang bisa diajukan.' });
        }

        draft.status = 'submitted';
        await draft.save();

        return res.status(200).json({
            message: 'Draf pengadaan berhasil diajukan ke Kaprodi.',
            data: draft
        });
    } catch (error) {
        console.error('Error submitting draft:', error);
        return res.status(500).json({ message: 'Gagal mengajukan draf pengadaan.' });
    }
};

// PUT review procurement detail item (Approve/Reject by Kaprodi)
const reviewItem = async (req, res) => {
    try {
        const { status } = req.body; // 'approved' or 'rejected'
        const detailId = req.params.detailId;

        if (!['approved', 'rejected'].includes(status)) {
            return res.status(400).json({ message: 'Status review harus approved atau rejected.' });
        }

        const detail = await ProcurementDetail.findByPk(detailId, {
            include: [{ model: ProcurementDraft, as: 'draft' }]
        });

        if (!detail) {
            return res.status(404).json({ message: 'Item detail pengadaan tidak ditemukan.' });
        }

        // Check if draft is locked
        if (detail.draft.status === 'finalized') {
            return res.status(400).json({ message: 'Draf sudah di-finalisasi dan dikunci.' });
        }

        detail.status = status;
        await detail.save();

        // Automatically update draft status to 'reviewed'
        if (detail.draft.status === 'submitted') {
            detail.draft.status = 'reviewed';
            await detail.draft.save();
        }

        return res.status(200).json({
            message: `Item berhasil di-${status}.`,
            data: detail
        });
    } catch (error) {
        console.error('Error reviewing item:', error);
        return res.status(500).json({ message: 'Gagal memproses review item pengadaan.' });
    }
};

// PUT finalize draft (Lock by Kaprodi)
const finalizeDraft = async (req, res) => {
    try {
        const draft = await ProcurementDraft.findByPk(req.params.id);
        if (!draft) {
            return res.status(404).json({ message: 'Draf pengadaan tidak ditemukan.' });
        }

        if (draft.status === 'draft') {
            return res.status(400).json({ message: 'Draf harus diajukan terlebih dahulu sebelum di-finalisasi.' });
        }

        draft.status = 'finalized';
        await draft.save();

        return res.status(200).json({
            message: 'Draf pengadaan berhasil di-finalisasi dan dikunci.',
            data: draft
        });
    } catch (error) {
        console.error('Error finalizing draft:', error);
        return res.status(500).json({ message: 'Gagal memfinalisasi draf pengadaan.' });
    }
};

module.exports = {
    getAllDrafts,
    getDraftById,
    createDraft,
    updateDraft,
    submitDraft,
    reviewItem,
    finalizeDraft
};
