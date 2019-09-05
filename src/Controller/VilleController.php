<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ville")
 */
class VilleController extends Controller
{
    /**
     * @Route("/", name="ville_index", methods={"GET"})
     */
    public function index(VilleRepository $villeRepository): Response
    {
        return $this->render('ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ville_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash("success", "La ville vient d'être ajoutée");
            return $this->redirectToRoute('ville_index');
        }

        return $this->render('ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ville_show", methods={"GET"})
     */
    public function show(Ville $ville): Response
    {
        return $this->render('ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ville_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ville $ville): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "La ville vient d'être modifiée");
            return $this->redirectToRoute('ville_index');
        }

        return $this->render('ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ville_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ville $ville): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ville);
            $entityManager->flush();
        }
        $this->addFlash("danger", "La ville vient d'être supprimée");
        return $this->redirectToRoute('ville_index');
    }

    /**
     * @Route("/recherche/", name="ville_recherche", methods={"GET"})
     */
    public function rechercher(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rechercher = true;
        $request= Request::createFromGlobals();
        $recherche= $request->query->get('recherche');
        $listeVilles = $entityManager->getRepository('App:Ville')->getByMotCle($recherche);
         return $this->render("ville/index.html.twig", ["listeVilles" => $listeVilles, "rechercher" => $rechercher]);


    }
}
