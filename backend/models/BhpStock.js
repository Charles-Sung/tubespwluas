const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const BhpStock = sequelize.define('BhpStock', {
    id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    item_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'items',
            key: 'id'
        }
    },
    total_quantity: {
        type: DataTypes.INTEGER,
        allowNull: false,
        defaultValue: 0
    }
}, {
    tableName: 'bhp_stocks',
    underscored: true
});

module.exports = BhpStock;
