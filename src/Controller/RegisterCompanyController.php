<?php

namespace App\Controller;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\ManagerRepository;
use App\Repository\WorkersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class RegisterCompanyController extends AbstractController
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
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        //   $token = $request->headers->get('token');
     //   $company = $request->headers->get('company');

         $company_name = $request->request->get('company');
         $email = $request->request->get('email');
         $password = $request->request->get('password');

         $company = new Company();
        $company -> setCompany($company_name);
        $company -> setEmail($email);
        $company -> setPassword($password);

        $this->emi->getConnection()->beginTransaction();
        try {
            $this-> emi -> persist($company);
            $this-> emi -> flush();
            $this-> emi -> commit();
            return $this-> response -> setContent(json_encode(['saved'=>'ok']));
        } catch (\Exception $exception) {
            $this -> emi -> rollback(); die('error register patch');
        }
    }



    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request){
        $role         = $request->request->get('role');
        $email        = $request->request->get('email');
        $password     = $request->request->get('password');

        if($role == 'boss'){
            $company = $this -> companyRepository -> findOneBy([
                'email'    => $email,
                'password' => $password ]);
            if( $company ) {
                return $this-> response -> setContent(json_encode([
                    'enter'    => 'ok',
                    'role'     => 'boss',
                    'id'       => $company->getId(),
                    'company'  => $company->getCompany(),
                    'email'    => $company->getEmail(),
                    'password' => $company->getPassword()
                ]));
            } else {
                $this-> response -> setContent(json_encode(['error' => true ]));
            }
        }


        if ($role == 'manager'){
            $manager = $this -> managerRepository -> findOneBy(['email'=>$email, 'password'=>$password]);

            if( $manager ) {
                $company_id = $manager -> getCId();
                $company = $this -> companyRepository -> findOneBy(['id'=>$company_id]);
                return $this-> response -> setContent(json_encode([
                    'enter'      => 'ok',
                    'role'       => 'manager',
                    'id'         => $company_id,
                    'id_manager' => $manager -> getId(),
                    'company'    => $company -> getCompany(),
                    'email'      => $manager -> getEmail(),
                    'password'   => $manager -> getPassword(),
                    'user'       => $manager -> getName()
                ]));
            } else {
                $this-> response -> setContent(json_encode(['error' => true ]));
            }
        }



        if ($role == 'worker'){
            $worker = $this -> workersRepository -> findOneBy(['email'=>$email, 'password'=>$password]);

            if($worker){
                $company_id = $worker -> getCId();
                $company = $this -> companyRepository -> findOneBy(['id'=>$company_id]);
                return $this-> response -> setContent(json_encode([
                    'enter'      => 'ok',
                    'role'       => 'worker',
                    'id'         => $company_id,
                    'id_worker'  => $worker -> getId(),
                    'company'    => $company -> getCompany(),
                    'email'      => $worker -> getEmail(),
                    'password'   => $worker -> getPassword(),
                    'user'       => $worker -> getName()
                ]));
            } else {
                $this-> response -> setContent(json_encode(['error' => true ]));
            }
        }


    }
}










