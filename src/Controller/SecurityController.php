<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {

            $this->redirectToRoute('sortie_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/recherche/", name="user_recherche", methods={"GET"})
     */
    public function rechercher(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rechercher = true;
        $request = Request::createFromGlobals();
        $recherche = $request->query->get('recherche');
        $listeUser = $entityManager->getRepository('App:User')->getByMotCle($recherche);
        return $this->render("ville/index.html.twig", ["users" => $listeUser, "rechercher" => $rechercher]);


    }
}