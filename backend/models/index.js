const { DataTypes } = require('sequelize');
const sequelize = require('../config/db');

// Role Model
const Role = sequelize.define('Role', {
    name: { type: DataTypes.STRING, allowNull: false }
});

// User Model
const User = sequelize.define('User', {
    name: { type: DataTypes.STRING, allowNull: false },
    email: { type: DataTypes.STRING, allowNull: false, unique: true },
    password: { type: DataTypes.STRING, allowNull: false }
});
User.belongsTo(Role, { foreignKey: 'role_id' });
Role.hasMany(User, { foreignKey: 'role_id' });

// Room Model
const Room = sequelize.define('Room', {
    name: { type: DataTypes.STRING, allowNull: false },
    description: { type: DataTypes.TEXT }
});

// Item Model
const Item = sequelize.define('Item', {
    name: { type: DataTypes.STRING, allowNull: false },
    type: { type: DataTypes.ENUM('inventory', 'bhp'), allowNull: false },
    description: { type: DataTypes.TEXT }
});

// ProcurementDraft Model
const ProcurementDraft = sequelize.define('ProcurementDraft', {
    title: { type: DataTypes.STRING, allowNull: false },
    year: { type: DataTypes.INTEGER, allowNull: false },
    status: { type: DataTypes.ENUM('draft', 'submitted', 'reviewed', 'finalized'), defaultValue: 'draft' }
});
ProcurementDraft.belongsTo(User, { foreignKey: 'user_id' }); // Kepala Lab

// ProcurementDetail Model
const ProcurementDetail = sequelize.define('ProcurementDetail', {
    quantity: { type: DataTypes.INTEGER, allowNull: false },
    price: { type: DataTypes.DECIMAL(15, 2), allowNull: false },
    purchase_link: { type: DataTypes.STRING },
    replaced_inventory_id: { type: DataTypes.INTEGER },
    status: { type: DataTypes.ENUM('pending', 'approved', 'rejected'), defaultValue: 'pending' }
});
ProcurementDetail.belongsTo(ProcurementDraft, { foreignKey: 'procurement_draft_id', onDelete: 'CASCADE' });
ProcurementDraft.hasMany(ProcurementDetail, { foreignKey: 'procurement_draft_id' });
ProcurementDetail.belongsTo(Item, { foreignKey: 'item_id' });

// Inventory Model
const Inventory = sequelize.define('Inventory', {
    label_number: { type: DataTypes.STRING, unique: true },
    qr_path: { type: DataTypes.STRING },
    condition: { type: DataTypes.ENUM('good', 'maintenance', 'broken', 'replaced'), defaultValue: 'good' }
});
Inventory.belongsTo(Item, { foreignKey: 'item_id' });
Inventory.belongsTo(Room, { foreignKey: 'room_id' });

// ItemReceipt Model
const ItemReceipt = sequelize.define('ItemReceipt', {
    quantity_received: { type: DataTypes.INTEGER, allowNull: false },
    receipt_date: { type: DataTypes.DATEONLY, allowNull: false },
    notes: { type: DataTypes.TEXT }
});
ItemReceipt.belongsTo(ProcurementDetail, { foreignKey: 'procurement_detail_id' });
ItemReceipt.belongsTo(User, { foreignKey: 'user_id' }); // Staf Admin

// BhpStock Model
const BhpStock = sequelize.define('BhpStock', {
    total_quantity: { type: DataTypes.INTEGER, defaultValue: 0 }
});
BhpStock.belongsTo(Item, { foreignKey: 'item_id', onDelete: 'CASCADE' });

// BhpTransaction Model
const BhpTransaction = sequelize.define('BhpTransaction', {
    type: { type: DataTypes.ENUM('in', 'out'), allowNull: false },
    quantity: { type: DataTypes.INTEGER, allowNull: false },
    date: { type: DataTypes.DATEONLY, allowNull: false },
    description: { type: DataTypes.STRING }
});
BhpTransaction.belongsTo(Item, { foreignKey: 'item_id' });

// MaintenanceLog Model
const MaintenanceLog = sequelize.define('MaintenanceLog', {
    maintenance_date: { type: DataTypes.DATEONLY, allowNull: false },
    description: { type: DataTypes.TEXT, allowNull: false }
});
MaintenanceLog.belongsTo(Inventory, { foreignKey: 'inventory_id' });
MaintenanceLog.belongsTo(User, { foreignKey: 'user_id' }); // Staf Lab

// MaintenanceBhpUsage Model
const MaintenanceBhpUsage = sequelize.define('MaintenanceBhpUsage', {
    quantity: { type: DataTypes.INTEGER, allowNull: false }
});
MaintenanceBhpUsage.belongsTo(MaintenanceLog, { foreignKey: 'maintenance_log_id', onDelete: 'CASCADE' });
MaintenanceBhpUsage.belongsTo(Item, { foreignKey: 'item_id' });

module.exports = {
    sequelize,
    Role,
    User,
    Room,
    Item,
    ProcurementDraft,
    ProcurementDetail,
    Inventory,
    ItemReceipt,
    BhpStock,
    BhpTransaction,
    MaintenanceLog,
    MaintenanceBhpUsage
};
