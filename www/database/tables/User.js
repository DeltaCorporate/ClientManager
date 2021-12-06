const {DataTypes, Model} = require('sequelize');
module.exports = class User extends Model {
    static init(sequelize) {
        return super.init({
            id: {
                type: DataTypes.INTEGER,
                autoIncrement: true,
                primaryKey: true
            },
            username: {
                type: DataTypes.STRING,
                allowNull: false,
                unique: true
            },
        }, {
            tableName: 'User',
            timestamps: true,
            sequelize
        });
    }
}