<?php
namespace App\Controller;
use App\Entity\Manager;
use App\Repository\CompanyRepository;
use App\Repository\ManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ManagerController extends AbstractController
{
    private $emi; private $response; private $managerRepository;
    public function __construct(EntityManagerInterface $emi, ManagerRepository $managerRepository){
        $this -> emi = $emi;
        $this -> managerRepository = $managerRepository;
        $this -> response = new Response();
        $this -> response -> headers -> set ('Content-Type', 'application/json');
        $this -> response -> headers -> set ('Access-Control-Allow-Origin', '*');
        $this -> response -> headers -> set ('Access-Control-Allow-Headers ', '*');
    }


    /**
     * @Route("/save_new_manager", name="save_new_manager")
     */
    public function index(Request $request)
    {
        $c_id = $request->request->get('c_id');
        $c_name = $request->request->get('c_name');
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $manager = new Manager();
        $manager -> setCId($c_id);
        $manager -> setCName($c_name);
        $manager -> setName($name);
        $manager -> setEmail($email);
        $manager -> setPassword($password);

        $this->emi->getConnection()->beginTransaction();

        try {
            $this-> emi -> persist($manager);
            $this-> emi -> flush();
            $this-> emi -> commit();

            return $this-> response -> setContent(json_encode(['saved'=>'ok']));
        } catch (\Exception $exception) {
            $this -> emi -> rollback(); die('error register patch');
        }
    }


    /**
     * @Route("/all_managers", name="all_managers")
     */
    public function getAllManagers(Request $request){
        $c_id = $request->request->get('c_id');
        $managers = $this ->  managerRepository-> findBy(['c_id' => $c_id]);

        $total = [ ];

        foreach ($managers as $key => $value){
            $arr = [];
            $arr['id'] = $managers[$key] -> getId();
            $arr['c_id'] = $managers[$key] -> getCId();
            $arr['c_name'] = $managers[$key] -> getCName();
            $arr['name'] = $managers[$key] -> getName();
            $arr['email'] = $managers[$key] -> getEmail();
            $arr['password'] = $managers[$key] -> getPassword();
            $total[] = $arr;
        }

        return $this -> response -> setContent(json_encode($total));
    }


    /**
     * @Route("/delete_manager", name="delete_manager")
     */
    public function deleteWorkId(Request $request){
        $id = $request->request->get('id');

        $manager = $this ->  managerRepository -> findOneBy(['id' => $id]);

        $this->emi->remove($manager);
        $this->emi->flush();

        return $this -> response -> setContent(json_encode(['ok' => 'ok']));
    }
}
