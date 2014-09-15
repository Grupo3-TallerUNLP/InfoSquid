<?php

namespace Grupo3TallerUNLP\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Grupo3TallerUNLPUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
