<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostUtilityController
 * @TODO Add Grant rules
 * @package App\Controller
 */
class PostUtilityController extends AbstractController
{
    /**
     * @Route("/account/tags/search", methods="GET", name="tags_search_autocomplete")
     */
    public function getTagsAutocomplete(TagRepository $tagRepository, Request $request): JsonResponse
    {
        $tags = $tagRepository->queryMatch($request->query->get('q'));
        $data = [];
        if (!empty($tags)) {
            $data = array_map(function ($el) {
                return [
                    'id' => $el->getId(),
                    'text' => $el->getName()
                ];
            }, $tags);
        }

        return $this->json($data, 200, [], ['groups' => ['main']]);
    }
}