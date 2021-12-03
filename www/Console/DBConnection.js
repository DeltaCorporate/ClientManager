const { Sequelize } = require("sequelize");

module.exports = new Sequelize(process.env.DATABASE_NAME, process.env.DATABASE_USER,process.env.DATABASE_PASSWORD,{
    host: process.env.DATABASE_HOST,
    dialect: "mysql",
    port:process.env.DATABASE_PORT
});