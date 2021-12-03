require("dotenv").config({
    path: 'configFiles/config.env'
});
const inquirer = require('inquirer');

const {createController, generateTable, migrateTables} = require("./Console/functions");


const questions = [
    {
        type: 'list',
        name: 'command',
        message: 'What do you want to do?',
        choices: [
            'Create Controller',
            'Create Table|Model',
            'Migrate Tables'
        ]
    },
    {
        type: 'input',
        name: 'controllerName',
        message: 'Controller Name:',
        when: (answers) => {
            return answers.command === 'Create Controller';
        }
    }, {
        type: 'input',
        name: 'modelName',
        message: 'Model Name:',
        when: (answers) => {
            return answers.command === 'Create Table|Model';
        }
    },
    {
        type: 'confirm',
        name: 'migrate',
        default: true,
        message: 'Are you sure you want to migrate actual tables?[default: yes]',
        when: (answers) => {
            return answers.command === 'Migrate Tables';
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
        case 'Create Table|Model':
            generateTable(answers.modelName).catch(r => {
                console.log(r);
            });
            break;
        case 'Migrate Tables':
            if (answers.migrate) {
                migrateTables().catch(r => {
                    console.log(r);
                });
            } else {
                console.log("Migration aborted");
            }
            break;
    }
}).catch(err => {
    console.log(err);
});

