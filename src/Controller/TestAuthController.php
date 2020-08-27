<?php
namespace App\Controller;
use App\Repository\CompanyRepository;
use App\Repository\ManagerRepository;
use App\Repository\WorkersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class TestAuthController extends AbstractController
{
    private $emi; private $response; private $companyRepository; private $managerRepository; private $workersRepository;
    public function __construct(EntityManagerInterface $emi, CompanyRepository $companyRepository, ManagerRepository $managerRepository, WorkersRepository $workersRepository){
        $this -> emi = $emi;
        $this -> companyRepository = $companyRepository;
        $this -> managerRepository = $managerRepository;
        $this -> workersRepository = $workersRepository;
        $this -> response = new Response();
        $this -> response -> headers -> set ('Content-Type', 'application/json');
        $this -> response -> headers -> set ('Access-Control-Allow-Origin', '*');
        $this -> response -> headers -> set ('Access-Control-Allow-Headers ', '*');
    }


    /**
     * @Route("/", name="start")
     */
    public function start()
    {
        return $this->response->setContent('autor: Alexei Suzdalenko');
    }

    /**
     * @Route("test/auth", name="test_auth")
     */
    public function index(Request $request)
    {
        $id = $request->query->get('id');
        $role = $request->query->get('role');
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        if ($role == 'boss') {
            $classComp = $this->companyRepository->findOneBy(['id' => $id]);
            if ($classComp->getEmail() == $email && $classComp->getPassword() == $password) {
                return $this->response->setContent(json_encode(['data' => true]));
            } else {
                return $this->response->setContent(json_encode(['data' => false]));
            }
        }

        if ($role == 'manager') {
            $classManager = $this-> managerRepository->findOneBy(['id' => $id]);

            if ($classManager->getEmail() == $email && $classManager->getPassword() == $password) {
                return $this->response->setContent(json_encode(['data' => true]));
            } else {
                return $this->response->setContent(json_encode(['data' => false]));
            }

        }

        if ($role == 'worker') {
            $classWorker = $this->workersRepository->findOneBy(['id' => $id]);

            if ($classWorker->getEmail() == $email && $classWorker->getPassword() == $password) {
                return $this->response->setContent(json_encode(['data' => true]));
            } else {
                return $this->response->setContent(json_encode(['data' => false]));
            }

        }
    }
}
