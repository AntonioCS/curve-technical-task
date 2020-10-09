<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Manager;

class ApiController extends AbstractController
{

    private Manager $managerService;

    public function __construct(Manager $managerService)
    {
        $this->managerService = $managerService;
    }

    /**
     * @Route("/api/rate", name="api_rate")
     */
    public function index() : JsonResponse
    {
        $result = $this->managerService->process();
        return $this->json($result);
    }
}
