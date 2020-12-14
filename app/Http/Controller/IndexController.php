<?php

namespace App\Http\Controller;

use App\Exceptions\ExceptionsHandle;
use App\Exceptions\NotFoundExceptionHandle;
use Steins\Exception\Exceptions\Http\NotFoundExceptions;
use Steins\Router\Attribute\Route;
use Steins\Router\Attribute\RouteGroup;

#[RouteGroup(prefix: '/user/')]
class IndexController
{
    #[Route('/{id}/profile')]
    #[ExceptionsHandle([NotFoundExceptionHandle::class])]
    public function profile(int $id)
    {
        throw new NotFoundExceptions();
//        return "<h1>Show user $id's profile</h1>";
    }
}