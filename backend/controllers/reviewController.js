const { ProcurementDraft, ProcurementDetail, Item } = require('../models');

exports.getSubmittedDrafts = async (req, res) => {
    try {
        const drafts = await ProcurementDraft.findAll({
            where: { status: ['submitted', 'reviewed', 'finalized'] },
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

exports.reviewDetail = async (req, res) => {
    try {
        const { id } = req.params; // ID of ProcurementDetail
        const { status } = req.body; // 'approved' or 'rejected'

        const detail = await ProcurementDetail.findByPk(id);
        if (!detail) return res.status(404).json({ message: 'Detail tidak ditemukan' });

        detail.status = status;
        await detail.save();

        res.json({ message: 'Status detail diupdate', detail });
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan' });
    }
};

exports.finalizeDraft = async (req, res) => {
    try {
        const { id } = req.params; // ID of ProcurementDraft
        const draft = await ProcurementDraft.findByPk(id);
        
        if (!draft) return res.status(404).json({ message: 'Draf tidak ditemukan' });

        draft.status = 'finalized';
        await draft.save();

        res.json({ message: 'Draf berhasil difinalisasi', draft });
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan' });
    }
};
