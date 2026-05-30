const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const ProcurementDraft = sequelize.define('ProcurementDraft', {
    id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    user_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'users',
            key: 'id'
        }
    },
    title: {
        type: DataTypes.STRING,
        allowNull: false
    },
    year: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    status: {
        type: DataTypes.ENUM('draft', 'submitted', 'reviewed', 'finalized'),
        allowNull: false,
        defaultValue: 'draft'
    }
}, {
    tableName: 'procurement_drafts',
    underscored: true
});

module.exports = ProcurementDraft;
