<?php


namespace Database;


abstract class migration
{
    public abstract function up();
    public abstract function down();
}