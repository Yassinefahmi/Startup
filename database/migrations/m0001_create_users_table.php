<?php


class m0001_create_users_table
{
    public function up()
    {
        \App\General\Application::$app->getDatabase()->getConnection()->exec("
            CREATE TABLE users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(30) NOT NULL,
                password VARCHAR(255) NOT NULL,
                gender ENUM('male', 'female', 'other') NOT NULL,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }

    public function down(){}
}