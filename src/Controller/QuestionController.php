<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Answer;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="list_question")
     */
    public function index(QuestionRepository $questionRepository): Response
    {

        $questions = $questionRepository->findAll();

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

        /**
     * @Route("/create", name="create_question")
     */
    public function create(Request $request): Response
    {

        $question = new Question();
    
        $question->setValidate(false);

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question = $formQuestion->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('list_question');
        }

        return $this->render('question/create.html.twig', [
            'question' => $question,
            'formquestion' => $formQuestion->createView(),
        ]);
    }

    
    /**
     * @Route("/{id}", name="show_question", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_question", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $formquestion = $this->createForm(QuestionType::class, $question);
        $formquestion->handleRequest($request);

        if ($formquestion->isSubmitted() && $formquestion->isValid()) {
            $question = $formquestion->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('list_question');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'formquestion' => $formquestion->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete_question", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('list_question');
    }
}
