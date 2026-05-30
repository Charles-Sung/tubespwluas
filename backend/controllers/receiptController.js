const { sequelize, ItemReceipt, ProcurementDetail, ProcurementDraft, Item, BhpStock, Inventory, User, Room } = require('../models');

// GET all approved items from finalized drafts (ready to be received)
const getPendingReceiptItems = async (req, res) => {
    try {
        const approvedItems = await ProcurementDetail.findAll({
            where: { status: 'approved' },
            include: [
                {
                    model: ProcurementDraft,
                    as: 'draft',
                    where: { status: 'finalized' },
                    attributes: ['id', 'title', 'year']
                },
                {
                    model: Item,
                    as: 'item',
                    attributes: ['id', 'name', 'type']
                },
                {
                    model: ItemReceipt,
                    as: 'receipts',
                    attributes: ['quantity_received']
                }
            ]
        });

        // Map and filter items that are not fully received yet
        const formattedItems = approvedItems.map(detail => {
            const totalReceived = detail.receipts.reduce((sum, r) => sum + r.quantity_received, 0);
            const remaining = detail.quantity - totalReceived;

            return {
                id: detail.id,
                procurement_draft_id: detail.procurement_draft_id,
                draft_title: detail.draft.title,
                draft_year: detail.draft.year,
                item_id: detail.item_id,
                item_name: detail.item.name,
                item_type: detail.item.type,
                quantity_ordered: detail.quantity,
                quantity_received_so_far: totalReceived,
                quantity_remaining: remaining,
                price: detail.price
            };
        }).filter(item => item.quantity_remaining > 0);

        return res.status(200).json(formattedItems);
    } catch (error) {
        console.error('Error fetching approved procurement items:', error);
        return res.status(500).json({ message: 'Gagal memuat barang siap penerimaan.' });
    }
};

// POST create receipt and update inventories / BHP stocks (Atomic Transaction)
const createReceipt = async (req, res) => {
    const t = await sequelize.transaction();
    try {
        const { procurement_detail_id, quantity_received, receipt_date, notes, room_id, label_numbers } = req.body;
        const user_id = req.user.id; // User Staf Admin

        if (!procurement_detail_id || !quantity_received || !receipt_date) {
            return res.status(400).json({ message: 'Detail pengadaan, kuantitas diterima, dan tanggal wajib diisi.' });
        }

        // Fetch detail item info
        const detail = await ProcurementDetail.findByPk(procurement_detail_id, {
            include: [
                { model: ProcurementDraft, as: 'draft' },
                { model: Item, as: 'item' },
                { model: ItemReceipt, as: 'receipts' }
            ],
            transaction: t
        });

        if (!detail) {
            return res.status(404).json({ message: 'Detail item pengadaan tidak ditemukan.' });
        }

        if (detail.draft.status !== 'finalized' || detail.status !== 'approved') {
            return res.status(400).json({ message: 'Barang hanya bisa diterima jika draf pengadaan sudah berstatus finalized oleh Kaprodi.' });
        }

        // Validate received quantity limit
        const totalReceived = detail.receipts.reduce((sum, r) => sum + r.quantity_received, 0);
        const remaining = detail.quantity - totalReceived;

        if (quantity_received > remaining) {
            return res.status(400).json({ message: `Jumlah diterima (${quantity_received}) melebihi sisa barang yang dipesan (${remaining}).` });
        }

        // Save receipt log
        const receipt = await ItemReceipt.create({
            procurement_detail_id,
            quantity_received,
            receipt_date,
            user_id,
            notes
        }, { transaction: t });

        // Update inventory or BHP stock based on item type
        if (detail.item.type === 'inventory') {
            if (!room_id) {
                return res.status(400).json({ message: 'Ruangan alokasi wajib diisi untuk barang bertipe inventaris.' });
            }

            if (!label_numbers || !Array.isArray(label_numbers) || label_numbers.length !== parseInt(quantity_received)) {
                return res.status(400).json({ message: `Harap masukkan ${quantity_received} nomor label unik untuk inventaris.` });
            }

            // Create inventories rows for each unit
            const inventoryRecords = label_numbers.map(label => ({
                item_id: detail.item_id,
                room_id: parseInt(room_id),
                label_number: label,
                qr_path: null, // Ready for QR code generation
                condition: 'good'
            }));

            // Check duplicate label numbers
            for (const record of inventoryRecords) {
                const existing = await Inventory.findOne({
                    where: { label_number: record.label_number },
                    transaction: t
                });
                if (existing) {
                    await t.rollback();
                    return res.status(400).json({ message: `Nomor label '${record.label_number}' sudah terdaftar di sistem. Gunakan label yang unik.` });
                }
            }

            await Inventory.bulkCreate(inventoryRecords, { transaction: t });
        } else if (detail.item.type === 'bhp') {
            // Find existing BHP stock
            const stock = await BhpStock.findOne({
                where: { item_id: detail.item_id },
                transaction: t
            });

            if (stock) {
                stock.total_quantity += parseInt(quantity_received);
                await stock.save({ transaction: t });
            } else {
                await BhpStock.create({
                    item_id: detail.item_id,
                    total_quantity: parseInt(quantity_received)
                }, { transaction: t });
            }
        }

        await t.commit();

        return res.status(201).json({
            message: 'Penerimaan barang berhasil dicatat dan inventaris/stok telah terupdate.',
            data: receipt
        });
    } catch (error) {
        await t.rollback();
        console.error('Error creating item receipt:', error);
        return res.status(500).json({ message: 'Gagal mencatat penerimaan barang.' });
    }
};

// GET all receipt logs
const getAllReceipts = async (req, res) => {
    try {
        const receipts = await ItemReceipt.findAll({
            include: [
                {
                    model: User,
                    as: 'user',
                    attributes: ['id', 'name']
                },
                {
                    model: ProcurementDetail,
                    as: 'detail',
                    include: [
                        { model: Item, as: 'item', attributes: ['id', 'name', 'type'] },
                        { model: ProcurementDraft, as: 'draft', attributes: ['id', 'title'] }
                    ]
                }
            ],
            order: [['created_at', 'DESC']]
        });
        return res.status(200).json(receipts);
    } catch (error) {
        console.error('Error fetching receipts:', error);
        return res.status(500).json({ message: 'Gagal mengambil data penerimaan barang.' });
    }
};

module.exports = {
    getPendingReceiptItems,
    createReceipt,
    getAllReceipts
};
