<?php

namespace App\Controller;



use App\Entity\Inscription;
use App\Entity\Sortie;
use App\Form\InscriptionType;
use App\Repository\InscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/inscription")
 */
class InscriptionController extends Controller
{
    /**
     * @Route("/", name="inscription_index", methods={"GET"})
     *
     */
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="inscription_deleteUser", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
/*
        if ($this->isCsrfTokenValid('delete'.$inscription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($inscription);
            $entityManager->flush();
            $this->addFlash("danger", "L'inscription vient d'être supprimée");
        }
*/
        return $this->redirectToRoute('sortie_index');
    }

    /**
     * @Route("/new/{id}", name="inscription_new", methods={"GET","POST"})
     */
    public function new(Request $request ,Sortie $sortie): Response
    {

        $inscription = new Inscription();

        $id = $sortie->getId();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);


            $inscription->setDateInscription((new \DateTime('now')));
            $inscription->setSortie($sortie);
            $inscription->setParticipant($this->get('security.token_storage')->getToken()->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inscription);
            $entityManager->flush();
            $this->addFlash("success", "L'inscription vient d'être ajoutée");
            return $this->redirectToRoute('sortie_index');


    }

    /**
     * @Route("/{id}", name="inscription_show", methods={"GET"})
     */
    public function show(Inscription $inscription): Response
    {
        return $this->render('inscription/show.html.twig', [
            'inscription' => $inscription,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="inscription_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Inscription $inscription): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "L'inscription vient d'être modifiée");
            return $this->redirectToRoute('inscription_index');
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
        ]);
    }

}
