<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class TareaController extends AbstractController
{
    /**
     * @Route("/", name="tarea_index", methods={"GET"})
     */
    public function index(TareaRepository $tareaRepository): Response
    {
        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tarea_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tarea);
            $entityManager->flush();

            return $this->redirectToRoute('tarea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="tarea_show", methods={"GET"})
     */
    public function show(Tarea $tarea): Response
    {
        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tarea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tarea $tarea): Response
    {
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tarea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_delete", methods={"POST"})
     */
    public function delete(Request $request, Tarea $tarea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tarea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tarea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tarea_index', [], Response::HTTP_SEE_OTHER);
    }
}
