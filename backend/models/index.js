const sequelize = require('../config/database');
const Role = require('./Role');
const User = require('./User');
const Room = require('./Room');
const Item = require('./Item');
const BhpStock = require('./BhpStock');
const Inventory = require('./Inventory');
const ProcurementDraft = require('./ProcurementDraft');
const ProcurementDetail = require('./ProcurementDetail');

// Define relationships

// Role <-> User
Role.hasMany(User, {
    foreignKey: 'role_id',
    as: 'users'
});
User.belongsTo(Role, {
    foreignKey: 'role_id',
    as: 'role'
});

// Item <-> BhpStock
Item.hasOne(BhpStock, {
    foreignKey: 'item_id',
    as: 'bhp_stock',
    onDelete: 'CASCADE'
});
BhpStock.belongsTo(Item, {
    foreignKey: 'item_id',
    as: 'item'
});

// Item <-> Inventory
Item.hasMany(Inventory, {
    foreignKey: 'item_id',
    as: 'inventories',
    onDelete: 'CASCADE'
});
Inventory.belongsTo(Item, {
    foreignKey: 'item_id',
    as: 'item'
});

// Room <-> Inventory
Room.hasMany(Inventory, {
    foreignKey: 'room_id',
    as: 'inventories',
    onDelete: 'CASCADE'
});
Inventory.belongsTo(Room, {
    foreignKey: 'room_id',
    as: 'room'
});

// User <-> ProcurementDraft (Kepala Lab)
User.hasMany(ProcurementDraft, {
    foreignKey: 'user_id',
    as: 'procurement_drafts'
});
ProcurementDraft.belongsTo(User, {
    foreignKey: 'user_id',
    as: 'user'
});

// ProcurementDraft <-> ProcurementDetail
ProcurementDraft.hasMany(ProcurementDetail, {
    foreignKey: 'procurement_draft_id',
    as: 'details',
    onDelete: 'CASCADE'
});
ProcurementDetail.belongsTo(ProcurementDraft, {
    foreignKey: 'procurement_draft_id',
    as: 'draft'
});

// Item <-> ProcurementDetail
Item.hasMany(ProcurementDetail, {
    foreignKey: 'item_id',
    as: 'procurement_details'
});
ProcurementDetail.belongsTo(Item, {
    foreignKey: 'item_id',
    as: 'item'
});

// Inventory <-> ProcurementDetail (Replaced Inventory)
Inventory.hasMany(ProcurementDetail, {
    foreignKey: 'replaced_inventory_id',
    as: 'replaced_procurements'
});
ProcurementDetail.belongsTo(Inventory, {
    foreignKey: 'replaced_inventory_id',
    as: 'replaced_inventory'
});

module.exports = {
    sequelize,
    Role,
    User,
    Room,
    Item,
    BhpStock,
    Inventory,
    ProcurementDraft,
    ProcurementDetail
};
