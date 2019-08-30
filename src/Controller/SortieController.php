<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
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
     * @Route("", name="sortie_index")
     */
    public function index(Request $request,SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(SortieRechercheType::class);
        $form->handleRequest($request);
        $site = $form->get('Site')->getData();
        $search = $form->get('search')->getData();
        $dateDebut = $form->get('dateDebut')->getData();
        $dateFin = $form->get('dateFin')->getData();


        if ($form->isSubmitted() && $form->isValid()) {

            if ($site===null && $search==null && $dateDebut == null && $dateFin == null){

                    //$listeSortie= $entityManager->getRepository('App:Sortie')->rechercheParSite($site);
                $listeSortie = $sortieRepository->findAll();
                return $this->render('sortie/index.html.twig', [
                    'sorties' => $listeSortie ,
                    'form' => $form->createView()
                ]);
            }elseif ($site && $search && $dateDebut && $dateFin){

                    // A modifier
                    $listeSortie=$entityManager->getRepository('App:Sortie')->findAll();

                    return $this->render('sortie/index.html.twig', [
                        'sorties' => $listeSortie ,
                        'form' => $form->createView()
                    ]);
                }elseif ($site && $search && $dateDebut && $dateFin==null){



                // A modifier
                $listeSortie=$entityManager->getRepository('App:Sortie')->rechercheSiteNomDateDebut($site,$search,$dateDebut);

                return $this->render('sortie/index.html.twig', [
                    'sorties' => $listeSortie ,
                    'form' => $form->createView()
                ]);


            }elseif ($site && $search && $dateDebut==null && $dateFin==null){



                // Liste des sites par nom recherché
                $listeSortie=$entityManager->getRepository('App:Sortie')->rechercheParSiteParRecherche($site,$search);

                return $this->render('sortie/index.html.twig', [
                    'sorties' => $listeSortie ,
                    'form' => $form->createView()
                ]);


            }elseif ($site && $search==null && $dateDebut==null && $dateFin==null){

                // Liste des sites
                $listeSortie=$entityManager->getRepository('App:Sortie')->rechercheParSite($site);

                return $this->render('sortie/index.html.twig', [
                    'sorties' => $listeSortie ,
                    'form' => $form->createView()
                ]);


            }








        }

        $listeSortie = $sortieRepository->findAll();

        return $this->render('sortie/index.html.twig', [
            'sorties' => $listeSortie ,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
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
