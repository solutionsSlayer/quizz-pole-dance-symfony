<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Player;
use App\Entity\Quizz;
use App\Form\QuizzType;
use App\Repository\QuizzRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quizz")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="email_request")
     */
    public function index(Request $request): Response
    {
        $player = new Player();
        
        $form = $this->createFormBuilder($player)
            ->add(
                'email', 
                EmailType::class,
                [
                    'help' => 'Donnez votre e-mail afin de pouvoir jouer au quizz!',
                ])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('check_quizz');
        }

        return $this->render('quizz/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/check", name="check_quizz")
     */
    public function check(QuizzRepository $quizzRepository)
    {
        $quizz = $quizzRepository->findAll();

        if (count($quizz) !== 0) {
            return $this->redirectToRoute('available_quizz');
        }

        return $this->render('quizz/not_available.html.twig');
    }

    /**
     * @Route("/list", name="list_quizz")
     */
    public function list(QuizzRepository $quizzRepository)
    {

        $quizzs = $quizzRepository->findAll();

        return $this->render('quizz/list.html.twig', [
            'quizzs' => $quizzs
        ]);

        return $this->render('quizz/test.html.twig');
    }

    /**
     * @Route("/available", name="available_quizz")
     */
    public function available(QuizzRepository $quizzRepository)
    {

        $quizzs = $quizzRepository->findAll();

        return $this->render('quizz/available.html.twig', [
            'quizzs' => $quizzs
        ]);
    }

    /**
     * @Route("/play/{id}", methods={"GET"}, name="play_quizz", requirements={"id"="\d+"})
     */
    public function play(QuizzRepository $quizzRepository, int $id)
    {
        $quizz = $quizzRepository->find($id);

        $countQuestion = 0;

        return $this->render('quizz/play.html.twig', [
            'count' => $countQuestion,
            'quizzUniqQuestion' => $quizz->getQuestions()[$countQuestion]->getContent(),
            'image' => $quizz->getQuestions()[$countQuestion]->getImage(),
            'quizz' => $quizz
        ]);
    }

    /**
     * @Route("/create", name="create_quizz")
     */
    public function quizz(Request $request): Response
    {

        $quizz = new Quizz();
        $quizz->setValidate(false);
        $quizz->setScore(0);

        $formQuizz = $this->createForm(QuizzType::class, $quizz);
        $formQuizz->handleRequest($request);

        if ($formQuizz->isSubmitted() && $formQuizz->isValid()) {
            $quizz = $formQuizz->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizz);
            $entityManager->flush();

            return $this->redirectToRoute('list_quizz');
        }

        return $this->render('quizz/create.html.twig', [
            'formQuizz' => $formQuizz->createView(),
        ]);



        return $this->render('quizz/test.html.twig');

    }
}
