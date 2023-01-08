Project Name : ToDo <br/>

ToDo is an application that allows your team to organize. They can handle their individual tasks while having visibility on each member's to do list.<br/>

<ul>
    <li>ToDo's users can add, toggle and and see the details of their tasks. They can also see the other member's of the staff tasks</li>
    <li> ToDo's admin have full rights on the team's tasks. They can also delete tasks from tha app. They have full power to manage the registered members.</li>
    <li>The Application comes with its phpUnit test battery</li>
</ul>

prerequisite :<br/>

<ul>
    <li> symfony : 6.1.4</li>
    <li> PHP : 8.1.7</li>
    <li> Apache : 2.4.47 </li>
    <li> SQL : 5.7.33 </li>
    <li> Developped with Laragon </li>
</ul>

Installing the app<br/>

<ol>    
    <li>Download the code files in your projects folder</li>
    <li>Throught your CLI, run "composer install" to get the needed dependances</li>
    <li>Create a database named : todo</li>
    <li>Create a test database named : todo_test</li>
    <li>Back throught your CLI, run "php bin/console make:migration"</li>
    <li>Once the migration is generated, run "php bin/console doctrine:migrations:migrate" to build your database according to the entities schemes</li>
    <li>Execute the ToDo.sql file included with the project file to populate the database</li>
    <li>Execute the ToDo_test.sql file included with the project file to populate the test database</li>
    <li>Connect to the admin account with this mail : "admin@adm.fr" ; and this password : "abcd1234".</li>
    <li>You can now access and use the ToDo Application !</li>
    <li>To run the test battery, run "php bin/phpunit" throught your CLI</li>
</ol>

© 2022 GitHub, Inc.
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About