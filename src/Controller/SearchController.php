<?php

namespace App\Controller;

use App\Helper\OpenSearchClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $openSearchClient;

    public function __construct(OpenSearchClient $openSearchClient)
    {
        $this->openSearchClient = $openSearchClient;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('page-1.html.twig');
    }

    /**
     * @Route("/page-2", name="search-page")
     */
    public function searchPage()
    {
        return $this->render('page-2.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $query = $request->query->get('q', '');
        $page = $request->query->getInt('page', 1);

        if (empty($query)) {
            return new JsonResponse([]);
        }

        $results = $this->openSearchClient->search($query, $page);

        return new JsonResponse($results);
    }
}