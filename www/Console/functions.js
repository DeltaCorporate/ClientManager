const fs = require('fs-extra');

exports.createController = async (name) => {
    const pathToCreate = `app/Controllers/${name}Controller.php`;
    let possibilityToCreate = await fs.pathExists(pathToCreate)

    if(possibilityToCreate){
        console.log(`${name}Controller already exists`);
    } else{
        const directory = name.split('/');
        if(directory.length>2){
            console.error('Le Controller ne peut etre qu\'un fichier du style Test ou alors un dossier du style Test/Test');
            return;
        }
        let namespace;
        let className;
        directory.length>1 ? namespace = 'App\\Controllers\\'+directory[0] : namespace = 'App\\Controllers';
        directory.length>1 ? className = directory[1] : namespace = 'App\\Controllers';
        className+="Controller";
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


exports.generateTable = async ($name)=>{

}