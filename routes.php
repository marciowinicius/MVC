<?php

$app->get('', 'HomeController@index');
$app->get('montagem', 'MontagemController@index');
$app->post('montagem/data', 'MontagemController@data');
