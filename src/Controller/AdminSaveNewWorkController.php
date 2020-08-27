<?php
namespace App\Controller;
use App\Entity\Work;
use App\Repository\CompanyRepository;
use App\Repository\PartRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
class AdminSaveNewWorkController extends AbstractController
{
    private $serializer; private $emi; private $response; private $worksRepository; private $partRepository;
    public function __construct(EntityManagerInterface $emi, WorkRepository $workRepository, PartRepository $partRepository){
        $this -> emi = $emi;
        $this -> worksRepository = $workRepository;
        $this -> partRepository = $partRepository;

        $this -> response = new Response();
        $this -> response -> headers -> set ('Content-Type', 'application/json');
        $this -> response -> headers -> set ('Access-Control-Allow-Origin', '*');
        $this -> response -> headers -> set ('Access-Control-Allow-Headers ', '*');
    }


    /**
     * @Route("/admin_save_new_work", name="admin_save_new_work")
     */
    public function adminSaveNewWork(Request $request)
    {
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $adress = $request->request->get('adress');
        $price = $request->request->get('price');
        $createdfor = $request -> request -> get('createdfor');

        $work = new Work();
        $work -> setCompany($id);
        $work -> setName($name);
        $work -> setAdress($adress);
        $work -> setPrice($price);
        $work -> setHours(0);
        $work -> setCreatedfor($createdfor);

        $this->emi->getConnection()->beginTransaction();

        try {
                $this-> emi -> persist($work);
                $this-> emi -> flush();
                $this-> emi -> commit();

            return $this-> response -> setContent(json_encode(['saved'=>'ok']));
        } catch (\Exception $exception) {
            $this -> emi -> rollback(); die('error register patch');
        }
    }


    /**
     * @Route("/all_works", name="all_works")
     */
    public function getAllWorks(Request $request){
        $id = $request->request->get('id');

        $works = $this ->  worksRepository-> findBy(['company'=>$id]);

        $total = [];

        foreach ($works as $key => $value){
            $arr = [];
            $arr['id'] = $works[$key] -> getId();
            $arr['name'] = $works[$key] -> getName();
            $arr['hours'] = $works[$key] -> getHours();
            $arr['price'] = $works[$key] -> getPrice();
            $arr['adress'] = $works[$key] -> getAdress();
            $arr['createdfor'] = $works[$key] -> getCreatedfor();
            $total[] = $arr;
        }

        return $this -> response -> setContent(json_encode($total));
    }


    /**
     * @Route("/delete_work", name="delete_work")
     */
    public function deleteWorkId(Request $request){
        $id = $request->request->get('id');

        $parts = $this -> partRepository -> findBy(['workid'=>$id]);
        foreach ($parts as $part) {
            $this->emi->remove($part);
        }

        $work = $this ->  worksRepository-> findOneBy(['id' => $id]);

        $this->emi->remove($work);
        $this->emi->flush();

        return $this -> response -> setContent(json_encode(['ok' => 'ok']));
    }
}
