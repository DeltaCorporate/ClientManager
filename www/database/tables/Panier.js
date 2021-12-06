const {DataTypes, Model} = require('sequelize');
const User = require("./User");
 class Panier extends Model {
    static init(sequelize) {
        return super.init({
            id: {
                type: DataTypes.INTEGER,
                autoIncrement: true,
                primaryKey: true
            },
            id_user: {
                type: DataTypes.INTEGER,
                allowNull: false,
                references: {
                    model: User,
                    key: 'id'
                }
            },
        }, {
            tableName: 'Panier',
            timestamps: true,
            sequelize
        });
    }
}


Panier.hasOne(User, {foreignKey: 'id_user'});


module.exports = new Panier();