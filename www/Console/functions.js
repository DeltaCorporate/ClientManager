const fs = require('fs-extra');
const fsnorm = require("fs")
const path = require('path');
const colors = require("colors");
const db = require("./DBConnection")

exports.createController = async (name) => {
    const pathToCreate = `app/Controllers/${name}Controller.php`;
    let possibilityToCreate = await fs.pathExists(pathToCreate)

    if (possibilityToCreate) {
        console.log(`${name}Controller already exists`);
    } else {
        const directory = name.split('/');
        if (directory.length > 2) {
            console.error('Le Controller ne peut etre qu\'un fichier du style Test ou alors un dossier du style Test/Test');
            return;
        }
        let namespace;
        let className;
        directory.length > 1 ? namespace = 'App\\Controllers\\' + directory[0] : namespace = 'App\\Controllers';
        directory.length > 1 ? className = directory[1] : className = directory[0];
        className += "Controller";
        const content = `<?php
namespace ${namespace};

use App\\Views\\Renderer;
class ${className}
    {
        //Develooper les fonctions ici
    
    }
    `
        fs.outputFile(pathToCreate, content, function (err) {
            if (err) throw err;
            console.log("Check your app/Controllers folder");
        });
    }

}


exports.generateTable = async (name) => {
    const pathForTable = `database/tables/${name}.js`;
    let possibilityToCreateTable = await fs.pathExists(pathForTable)

    const pathForModel = `app/Models/${name}.php`;
    let possibilityToCreateModel = await fs.pathExists(pathForModel)
    if (possibilityToCreateTable) {
        console.log(`${name} model already exists`);
    } else {
        if (possibilityToCreateModel) {
            console.log(`${name} model already exists`);
        } else {
            const model = `const {DataTypes, Model} = require('sequelize');
module.exports = class ${name} extends Model {
    static init(sequelize) {
        return super.init({
            id: {
                type: DataTypes.INTEGER,
                autoIncrement: true,
                primaryKey: true
            },
            //Ajouter des colonnes ici
        }, {
            tableName: '${name}',
            timestamps: true,
            sequelize
        });
    }
}`
            const phpModel = `<?php

namespace App\\Models;

class ${name} extends Model
{
  
}
`
            await fs.outputFile(pathForModel, phpModel, function (err) {
                if (err) throw err;
                console.log("Check your app/Models folder");
            });
            await fs.outputFile(pathForTable, model, function (err) {
                if (err) throw err;
                console.log("Check your database/tables folder to edit the table columns");
            });
        }
    }
}

exports.migrateTables = async ()=>{
    const files = fsnorm.readdirSync("database/tables");
    console.log(files);
    let models = []
    files.forEach((e) => {
        try {
            const fileName = e.split(".")[0];
            const file = require(path.join("../database/tables", e));
            models.push(file)
        } catch (error) {
            console.log(error)
        }
    });
    if (models.length > 0) {
        await db.authenticate().then(() => {
            console.log(colors.red("###########################################################"))
            console.log(colors.cyan("BASE DE DONNEES CONNECTEE"))
            models.forEach(model => {
                model.init(db)
                model.sync({alter: true})
            })
        }).catch(err=>{
            console.log(colors.red("###########################################################"))
            console.log(colors.red("ERREUR DE CONNEXION A LA BASE DE DONNEES"))
            console.log(colors.red(err))
        })
    }
};
