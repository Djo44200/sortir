<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieCancelType;
use App\Form\SortieModificationType;
use App\Form\SortieRechercheType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends Controller
{
    /**
     * @Route("", name="sortie_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieRechercheType::class);
        $form->handleRequest($request);
        $site = $form->get('Site')->getData();
        $search = $form->get('search')->getData();
        $dateDebut = $form->get('dateDebut')->getData();
        $dateFin = $form->get('dateFin')->getData();
        $userOrgan = $form->get('userOrgan')->getData();
        $userInscris = $form->get('userInscris')->getData();
        $userNonInscri = $form->get('userNonInscris')->getData();
        $sortiePassee = $form->get('sortiePassee')->getData();
        //Check des sorties à supprimer
        $listeSortieACloturer = $entityManager->getRepository('App:Sortie')->rechercheParCloture();
        // Mettre la liste des sorties en état PAS
        if ($listeSortieACloturer) {
            foreach ($listeSortieACloturer as $sortie) {
                $sortie->setEtat('PAS');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }
        }
            $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
            if ($form->isSubmitted() && $form->isValid()) {
                if ($site || $search || $dateDebut || $dateFin || $userOrgan || $userInscris || $userNonInscri || $sortiePassee) {
                    // Recherche par site
                    if ($site) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParSite($site);
                    }
                    // Recherche par nom ( contient )
                    if ($search) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParNom($search);
                    }
                    // Recherche par date de début de la sortie
                    if ($dateDebut) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParDateDebut($dateDebut);
                    }
                    // Recherche par date de fin de sortie
                    if ($dateFin) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParDateFin($dateFin);
                    }
                    if ($dateDebut && $dateFin) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->recherchePardateDebutDateFin($dateDebut, $dateFin);
                    }
                    // Recherche par check user est organisateur
                    if ($userOrgan) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParUserOrga($userId);
                    }
                    // Recherche par check user est inscris à une sortie
                    if ($userInscris) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParUserInscris($userId);
                    }
                    // Recherche par check user est nom incris à une sortie
                    if ($userNonInscri) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParUserNonInscris($userId);
                    }
                    // Recherche par check d'une sortie passée
                    if ($sortiePassee) {
                        $listeSortie = $entityManager->getRepository('App:Sortie')->rechercheParSortiePassee();
                    }

                    return $this->render('sortie/index.html.twig', [
                        'sorties' => $listeSortie,
                        'form' => $form->createView()
                    ]);
                }
                $listeSortie = $sortieRepository->findAll();
                return $this->render('sortie/index.html.twig', [
                    'sorties' => $listeSortie,
                    'form' => $form->createView()
                ]);
            }
            // Affiche la liste de toutes les sorties
            $listeSortie = $sortieRepository->findAll();
            return $this->render('sortie/index.html.twig', [
                'sorties' => $listeSortie,
                'form' => $form->createView()
            ]);


    }

    /**
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //pour formulaire sortie
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        //pour formulaire lieu dans la modal
        $lieu = new Lieu();
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formLieu->handleRequest($request);
        //pour formulaire ville dans la modal
        $ville = new Ville();
        $formVille = $this->createForm(VilleType::class, $ville);
        $formVille->handleRequest($request);
        //recuperation du site de l'utilisateur qui cree la sortie
        $userId = [$this->get('security.token_storage')->getToken()->getUser()->getId()];
        $siteUser = $entityManager->getRepository('App:User')->rechercheSiteUser($userId[0]);


        //recuperation des villes
        $villes = $this->getDoctrine()->getRepository(Ville::class)->findAll();
        //recuperation des lieux
        $lieux = $this->getDoctrine()->getRepository(Lieu::class)->findAll();
        //validation formulaire sortie
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('saveAndPublish')->isClicked()) {
                $sortie->setEtat(Sortie::ETAT_OUVERTE);
            }
            if ($form->get('save')->isClicked()) {
                $sortie->setEtat(Sortie::ETAT_CREE);
            }

            //recuperation de l'organisateur pour l'ajouter a la sortie en BDD
            $sortie->setOrganisateur($this->getUser());
            //recuperation du site organisateur pour l'ajouter a la sortie en BDD


            $sortie->setSite($this->getUser()->getSite());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash("info", "La sortie vient d'être créée");
            return $this->redirectToRoute('sortie_index', ['ville' => $villes, 'lieux' => $lieux, 'siteUser' => $siteUser]);
        }
        //validation formulaire lieu
        //si creation de lieu avec la modale, ajout en BDD
        if ($formLieu->isSubmitted() && $formLieu->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lieu);
            $entityManager->flush();
            $this->addFlash("info", "Le lieu vient d'être créé");
        }
        //validation formulaire ville
        //si creation de ville avec la modale, ajout en BDD
        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash("info", "La ville vient d'être créé");
        }
        return $this->render('sortie/new.html.twig', [
            'form' => $form->createView(),
            'sortie' => $sortie,
            'siteUser' => $siteUser,
            'formLieu' => $formLieu->createView(),
            'lieu' => $lieu,
            'formVille' => $formVille->createView(),
            'ville' => $ville
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $id = $sortie->getId();
        //  Avoir la liste des participants d'une sortie
        $listeParticipant = $entityManager->getRepository('App:Inscription')->findBy(['sortie' => $sortie]);
        //$listeParticipant = $entityManager->getRepository('User')->findBy(['inscriptions' => ])
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie, 'listeParticipant' => $listeParticipant
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
        //pour formulaire sortie
        $form = $this->createForm(SortieModificationType::class, $sortie);
        $form->handleRequest($request);
        $boutonPublier = $request->get('publier');
        //pour formulaire lieu dans la modal
        $lieu = new Lieu();
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formLieu->handleRequest($request);
        //pour formulaire ville dans la modal
        $ville = new Ville();
        $formVille = $this->createForm(VilleType::class, $ville);
        $formVille->handleRequest($request);
        //recuperation du site de l'utilisateur qui cree la sortie
        $siteUser = $this->getDoctrine()->getRepository(Site::class)->findById([$this->get('security.token_storage')->getToken()->getUser()->getId()]);
        //recuperation des villes
        $villes = $this->getDoctrine()->getRepository(Ville::class)->findAll();
        //recuperation des lieux
        $lieux = $this->getDoctrine()->getRepository(Lieu::class)->findAll();
        //validation formulaire sortie
        if ($form->isSubmitted() && $form->isValid()) {

            if ($boutonPublier=="") {
                $sortie->setEtat(Sortie::ETAT_OUVERTE);
            }
            //recuperation de l'organisateur pour l'ajouter a la sortie en BDD
            $sortie->setOrganisateur($this->get('security.token_storage')->getToken()->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash("info", "La sortie vient d'être modifiée");
            return $this->redirectToRoute('sortie_index', ['ville' => $villes, 'lieux' => $lieux, 'siteUser' => $siteUser]);
        }
        //validation formulaire lieu
        //si creation de lieu avec la modale, ajout en BDD
        if ($formLieu->isSubmitted() && $formLieu->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lieu);
            $entityManager->flush();
            $this->addFlash("info", "Le lieu vient d'être créé");
            // essai de redirection vers page precedente : return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        //validation formulaire ville
        //si creation de ville avec la modale, ajout en BDD
        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash("info", "La ville vient d'être créé");
        }
        return $this->render('sortie/edit.html.twig', [
            'form' => $form->createView(),
            'sortie' => $sortie,
            'siteUser' => $siteUser,
            'formLieu' => $formLieu->createView(),
            'lieu' => $lieu,
            'formVille' => $formVille->createView(),
            'ville' => $ville
        ]);
    }

    /**
     * @Route("/{id}/cancel", name="sortiecancel", methods={"GET","POST"})
     */
    public function cancel(Request $request, Sortie $sortie): Response
    {
        $form = $this->createForm(SortieCancelType::class, $sortie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setEtat(Sortie::ETAT_ANNULLE);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "La sortie vient d'être annulée");
            return $this->redirectToRoute('sortie_index');
        }
        return $this->render('sortie/cancel.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash("danger", "La sortie vient d'être supprimée");
        }
        return $this->redirectToRoute('sortie_index');
    }


}