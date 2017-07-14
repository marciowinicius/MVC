<?php

use core\Dbmap\DBlueprint;
use core\Dbmap\DataTable;

DataTable::run()->createTable('montagem', function (DBlueprint $table) {
    $table->int('id', 11)->increments()->primaryKey();
    $table->varchar('codigo', 15)->notNull()->unique();
    $table->int('ano_fabricacao', 4)->notNull();
    $table->int('ano_modelo', 4)->notNull();
    $table->varchar('cor', 20)->notNull();
    $table->enum('completo', ['SIM', 'NAO']);
    return $table;
});
