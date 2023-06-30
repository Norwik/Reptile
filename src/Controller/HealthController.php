<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Health Controller
 */
class HealthController extends AbstractController
{
    #[Route('/_health', name: 'app_health')]
    public function index(LoggerInterface $logger): JsonResponse
    {
        $logger->info('Incoming request to health page');

        return $this->json([
            'status' => 'ok',
        ]);
    }
}
