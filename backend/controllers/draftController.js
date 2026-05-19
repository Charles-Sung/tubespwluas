const { ProcurementDraft, ProcurementDetail, Item } = require('../models');

exports.createDraft = async (req, res) => {
    try {
        const { title, year, details } = req.body;
        
        // Buat Draft
        const draft = await ProcurementDraft.create({
            title,
            year,
            user_id: req.user.id
        });

        // Buat detail (Item yang dibeli)
        if (details && details.length > 0) {
            const draftDetails = details.map(item => ({
                procurement_draft_id: draft.id,
                item_id: item.item_id,
                quantity: item.quantity,
                price: item.price,
                purchase_link: item.purchase_link,
                replaced_inventory_id: item.replaced_inventory_id || null
            }));
            await ProcurementDetail.bulkCreate(draftDetails);
        }

        res.status(201).json({ message: 'Draf berhasil dibuat', draft });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Terjadi kesalahan' });
    }
};

exports.getDrafts = async (req, res) => {
    try {
        const drafts = await ProcurementDraft.findAll({
            where: { user_id: req.user.id },
            include: [{
                model: ProcurementDetail,
                include: [Item]
            }]
        });
        res.json(drafts);
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan' });
    }
};

exports.submitDraft = async (req, res) => {
    try {
        const draft = await ProcurementDraft.findByPk(req.params.id);
        if (!draft) return res.status(404).json({ message: 'Draf tidak ditemukan' });
        
        if (draft.user_id !== req.user.id) return res.status(403).json({ message: 'Akses ditolak' });

        draft.status = 'submitted'; // Locked
        await draft.save();

        res.json({ message: 'Draf berhasil di-submit', draft });
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan' });
    }
};
