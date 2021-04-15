<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiQuestionController extends AbstractController
{
    /**
     * @Route("/api/question", name="api_question_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('api_question/index.html.twig', [
            'controller_name' => 'ApiQuestionController',
        ]);
    }
}
