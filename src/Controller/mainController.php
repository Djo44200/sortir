<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class mainController extends Controller
{
    /**
     * @Route("", name="index")
     */
    public function main(){

        if ($this->getUser()){
            return $this->redirectToRoute('sortie_index');

        }

        return $this->redirectToRoute('app_login');
    }

}