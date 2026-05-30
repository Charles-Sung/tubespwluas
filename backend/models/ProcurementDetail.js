const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const ProcurementDetail = sequelize.define('ProcurementDetail', {
    id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    procurement_draft_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'procurement_drafts',
            key: 'id'
        }
    },
    item_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'items',
            key: 'id'
        }
    },
    quantity: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    price: {
        type: DataTypes.DECIMAL(15, 2),
        allowNull: false
    },
    purchase_link: {
        type: DataTypes.STRING,
        allowNull: true
    },
    replaced_inventory_id: {
        type: DataTypes.INTEGER,
        allowNull: true,
        references: {
            model: 'inventories',
            key: 'id'
        }
    },
    status: {
        type: DataTypes.ENUM('pending', 'approved', 'rejected'),
        allowNull: false,
        defaultValue: 'pending'
    }
}, {
    tableName: 'procurement_details',
    underscored: true
});

module.exports = ProcurementDetail;
