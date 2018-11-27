<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Form\SportType;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sport")
 */
class SportController extends Controller
{
    /**
     * @Route("/", name="sport_index", methods="GET")
     */
    public function index(SportRepository $sportRepository): Response
    {
        return $this->render('sport/index.html.twig', ['sports' => $sportRepository->findAll()]);
    }

    /**
     * @Route("/new", name="sport_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $sport = new Sport();
        $form = $this->createForm(SportType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            return $this->redirectToRoute('sport_index');
        }

        return $this->render('sport/new.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sport_show", methods="GET")
     */
    public function show(Sport $sport): Response
    {
        return $this->render('sport/show.html.twig', ['sport' => $sport]);
    }

    /**
     * @Route("/{id}/edit", name="sport_edit", methods="GET|POST")
     */
    public function edit(Request $request, Sport $sport): Response
    {
        $form = $this->createForm(SportType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sport_index', ['id' => $sport->getId()]);
        }

        return $this->render('sport/edit.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sport_delete", methods="DELETE")
     */
    public function delete(Request $request, Sport $sport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sport->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sport);
            $em->flush();
        }

        return $this->redirectToRoute('sport_index');
    }
}
