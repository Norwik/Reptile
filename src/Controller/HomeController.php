<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Home Controller
 */
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(LoggerInterface $logger): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to Home Controller!',
        ]);
    }
}
