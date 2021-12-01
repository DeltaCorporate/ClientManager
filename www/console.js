require("dotenv").config({
    path:'configFiles/config.env'
});
const inquirer = require('inquirer');

const {createController, generateTable} = require("./Console/functions");


const questions = [
    {
        type: 'list',
        name: 'command',
        message: 'What do you want to do?',
        choices: [
            'Create Controller',
            'Create Table|Model|Migration',
        ]
    },
    {
        type: 'input',
        name: 'controllerName',
        message: 'Controller Name:',
        when: (answers) => {
            return answers.command === 'Create Controller';
        }
    },{
        type: 'input',
        name: 'modelName',
        message: 'Model Name:',
        when: (answers) => {
            return answers.command === 'Create Table|Model|Migration';
        }
    }
];
inquirer.prompt(questions).then(answers => {
    switch (answers.command) {
        case 'Create Controller':
            createController(answers.controllerName).catch(r => {
                console.log(r);
            });
            break;
        case 'Create Table|Model|Migration':
            generateTable(answers.modelName).catch(r => {
                console.log(r);
            });
            break;
    }
}).catch(err=>{
    console.log(err);
});

