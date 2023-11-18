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
        return $this->render('search.html.twig');
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

    private function performOpenSearch(string $query, int $page, int $perPage = 5): array
    {
        // Placeholder array of results
        $allResults = [
            ['id' => 1, 'name' => 'Result 1'],
            ['id' => 2, 'name' => 'Result 2'],
            ['id' => 3, 'name' => 'Result 3'],
            ['id' => 4, 'name' => 'Result 4'],
            ['id' => 5, 'name' => 'Result 5'],
            ['id' => 6, 'name' => 'Result 6'],
            ['id' => 7, 'name' => 'Result 7'],
            ['id' => 8, 'name' => 'Result 8'],
            ['id' => 9, 'name' => 'Result 9'],
            ['id' => 10, 'name' => 'Result 10'],
            ['id' => 11, 'name' => 'Result 11'],
        ];

        // Paginate the array
        return $this->paginateArray($allResults, $page, $perPage);
    }

    private function paginateArray(array $data, int $page, int $perPage = 10): array
    {
        $totalItems = count($data);
        $offset = ($page - 1) * $perPage;

        if ($offset >= $totalItems) {
            return [];
        }

        return array_slice($data, $offset, $perPage);
    }
}