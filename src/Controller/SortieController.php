<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieRechercheType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class SortieController extends Controller
{
    /**
     * @Route("", name="sortie_index", methods={"GET"})
     */
    public function index(Request $request,SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {

        $listeSite = $listeParticipant = $entityManager->getRepository('App:Site')->findAll();
        $form = $this->createForm(SortieRechercheType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(), 'ListeSite'=>$listeSite,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        //recuperation du site de l'utilisateur qui cree la sortie
        $siteUser = $this->getDoctrine()->getRepository(Site::class)->findById([$this->get('security.token_storage')->getToken()->getUser()->getId()]);
        //recuperation des villes
        $villes = $this->getDoctrine()->getRepository(Ville::class)->findAll();
        //recuperation des lieux
        $lieux = $this->getDoctrine()->getRepository(Lieu::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('saveAndPublish')->isClicked()) {
                $sortie->setEtat(Sortie::ETAT_OUVERTE);
            }
            if ($form->get('save')->isClicked()) {
                $sortie->setEtat(Sortie::ETAT_CREE);
            }

            //recuperation de l'organisateur pour l'ajouter a la sortie en BDD
            $sortie->setOrganisateur($this->get('security.token_storage')->getToken()->getUser());
            //recuperation du site organisateur pour l'ajouter a la sortie en BDD
            $sortie->setSite($siteUser[0]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index', ['ville'=>$villes, 'lieux'=>$lieux, 'siteUser' => $siteUser]);
        }
        return $this->render('sortie/new.html.twig', [
            'form' => $form->createView(),
            'sortie' => $sortie,
            'siteUser' => $siteUser
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie,EntityManagerInterface $entityManager): Response
    {
        $id=$sortie->getId();
        //  Avoir la liste des participants d'une sortie

        $listeParticipant = $entityManager->getRepository('App:Inscription')->findBy(['sortie' => $sortie]);


        //$listeParticipant = $entityManager->getRepository('User')->findBy(['inscriptions' => ])
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,'listeParticipant'=>$listeParticipant
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }
}
