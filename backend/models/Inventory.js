const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const Inventory = sequelize.define('Inventory', {
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
    room_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'rooms',
            key: 'id'
        }
    },
    label_number: {
        type: DataTypes.STRING,
        allowNull: false
    },
    condition: {
        type: DataTypes.ENUM('good', 'maintenance', 'broken'),
        allowNull: false,
        defaultValue: 'good'
    }
}, {
    tableName: 'inventories',
    underscored: true
});

module.exports = Inventory;
