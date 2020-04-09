<?php


namespace App\Controller;


use App\Service\PDO_manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    private $pdo;

    public function __construct(PDO_manager $pdo){
        $this->pdo = $pdo;
    }


    /**
     * @Route("/steden", name="steden_show")
     */
    //version Jordi
    public function getSteden(){
        $rows = $this->pdo->GetData('SELECT * FROM city INNER JOIN images on cit_img_id = img_id');
        return $this->render('city/city.html.twig', ['rows'=>$rows]);
    }

}