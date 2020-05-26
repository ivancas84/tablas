<?php

require("../config/config.php"); 

require_once("class/model/Entity.php");

require("phpdbgen/PhpDbGen.php");
$php = new PhpDbGen(Entity::getStructure());
$php->generate();
