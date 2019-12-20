<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{

    /**
     * @Route("/bonjour/{prenom}/age/{age}", name="hello", requirements={"age"="\d+"})
     * @Route("/bonjour", name="hello_base")
     * @Route("/bonjour/{prenom}", name="hello_prenom")
     * Montre la page qui dit bonjour
     *
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0) {
        // return new Response("Bonjour " .$prenom .". Vous avez ".$age." an(s)" );
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }
    
    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        
        $prenoms = ["Lior" => 10, "Joseph" => 51, "Anne" => 30];
        
        return $this->render(
            'home.html.twig',
            [ 
                'title' => "bonjour",
                'age' => 17,
                'tableau' => $prenoms
            ]
        );
    }

}

?>