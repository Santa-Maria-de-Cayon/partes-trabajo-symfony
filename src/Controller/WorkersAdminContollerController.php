<?php
namespace App\Controller;
use App\Entity\Workers;
use App\Repository\ManagerRepository;
use App\Repository\WorkersRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class WorkersAdminContollerController extends AbstractController
{
    private $emi; private $response; private $workersRepository;
    public function __construct(EntityManagerInterface $emi, WorkersRepository $workersRepository){
        $this -> emi = $emi;
        $this -> workersRepository = $workersRepository;
        $this -> response = new Response();
        $this -> response -> headers -> set ('Content-Type', 'application/json');
        $this -> response -> headers -> set ('Access-Control-Allow-Origin', '*');
        $this -> response -> headers -> set ('Access-Control-Allow-Headers ', '*');
    }

    /**
     * @Route("/save_worker", name="save_worker")
     */
    public function index(Request $request)
    {
        $c_id = $request->request->get('c_id');
        $c_name = $request->request->get('c_name');
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $createdfor = $request->request->get('createdfor');

        $manager = new Workers();
        $manager -> setCId($c_id);
        $manager -> setCName($c_name);
        $manager -> setName($name);
        $manager -> setEmail($email);
        $manager -> setPassword($password);
        $manager -> setCreatedfor($createdfor);

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
     * @Route("/all_workers", name="all_workers")
     */
    public function getAllManagers(Request $request){
        $c_id = $request->request->get('c_id');
        $workers = $this ->  workersRepository -> findBy(['c_id' => $c_id]);

        $total = [ ];

        foreach ($workers as $key => $value){
            $arr = [];
            $arr['id'] = $workers[$key] -> getId();
            $arr['c_id'] = $workers[$key] -> getCId();
            $arr['c_name'] = $workers[$key] -> getCName();
            $arr['name'] = $workers[$key] -> getName();
            $arr['email'] = $workers[$key] -> getEmail();
            $arr['password'] = $workers[$key] -> getPassword();
            $arr['createdfor'] = $workers[$key] -> getCreatedfor();
            $total[] = $arr;
        }

        return $this -> response -> setContent(json_encode($total));
    }


    /**
     * @Route("/delete_worker", name="delete_worker")
     */
    public function deleteWorkId(Request $request){
        $id = $request->request->get('id');

        $worker = $this ->  workersRepository -> findOneBy(['id' => $id]);

        $this->emi->remove($worker);
        $this->emi->flush();

        return $this -> response -> setContent(json_encode(['ok' => 'ok']));
    }
}
