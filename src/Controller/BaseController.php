<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method User getUser()
 */
class BaseController extends AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * BaseController constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack) {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return User
     */
//    protected function getUser(): User
//    {
//        return parent::getUser();
//    }
}