<?php

namespace app\controller;

use core\Controller;

class HomeController extends Controller {

    public function principal() {

        $this->toRender('principal.php', []);
    }

}
