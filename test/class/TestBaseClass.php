<?php

namespace Firaiz\Ufl\TestClass;

use Firaiz\Ufl\Base;

class TestBaseClass extends Base
{

    public function execute(): void
    {
        // empty
    }
    public function getConfig()
    {
        return $this->conf;
    }
}