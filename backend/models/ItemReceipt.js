const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const ItemReceipt = sequelize.define('ItemReceipt', {
    id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    procurement_detail_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'procurement_details',
            key: 'id'
        }
    },
    quantity_received: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    receipt_date: {
        type: DataTypes.DATEONLY,
        allowNull: false
    },
    user_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'users',
            key: 'id'
        }
    },
    notes: {
        type: DataTypes.TEXT,
        allowNull: true
    }
}, {
    tableName: 'item_receipts',
    underscored: true
});

module.exports = ItemReceipt;
