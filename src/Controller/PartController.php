<?php
namespace App\Controller;
use App\Entity\Part;
use App\Repository\PartRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class PartController extends AbstractController
{
    private $emi; private $response; private $partsRepository; private $workRepository;
    public function __construct(EntityManagerInterface $emi, PartRepository $partRepository, WorkRepository $workRepository){
        $this -> emi = $emi;
        $this -> partsRepository = $partRepository;
        $this -> workRepository = $workRepository;
        $this -> response = new Response();
        $this -> response -> headers -> set ('Content-Type', 'application/json');
        $this -> response -> headers -> set ('Access-Control-Allow-Origin', '*');
        $this -> response -> headers -> set ('Access-Control-Allow-Headers ', '*');
    }

    /**
     * @Route("/get_parts", name="get_parts")
     */
    public function index(Request $request){
        $id = $request->request->get('id');
        $parts = $this ->  partsRepository -> findBy(['workid' => $id]);

        $total = [];
        foreach ($parts as $key => $value){
            $arr = [];
            $arr['id'] =       $parts[$key] -> getId();
            $arr['data'] =     $parts[$key] -> getData();
            $arr['user'] =     $parts[$key] -> getUser();
            $arr['time'] =     $parts[$key] -> getTime();
            $arr['todo']   =     $parts[$key] -> getDo();
            $arr['workid'] =   $parts[$key] -> getWorkid();
            $arr['createdfor']=$parts[$key] -> getCreatedfor();
            $arr['material']  =$parts[$key] -> getMaterial();
            $total[] = $arr;
        }
        return $this -> response -> setContent(json_encode($total));
    }

    /**
     * @Route("/save_part", name="save_part")
     */
    public function saveNewPart(Request $request){
        $date = $request -> request -> get('date');
        $user = $request -> request -> get('user');
        $time = $request -> request -> get('time');
        $todow = $request -> request -> get('todo');
        $material = $request -> request -> get('material');
        $workid = $request -> request ->get('workid');
        $createdfor = $request -> request -> get('createdfor');

        if($time < 0) $time = 0;
        $work = $this -> workRepository -> find(['id'=>$workid]);

        if($work){
            $lastHoursWork = $work ->getHours();
            $sumTime = $time + $lastHoursWork;
            if ($sumTime > 999999999999) {
                return $this -> response -> setContent(json_encode(['saved'=>'ok','timer'=>0]));
            };
            $work -> setHours($sumTime);
        }

        $part = new Part();
        $part -> setData($date);
        $part -> setUser($user);
        $part -> setTime($time);
        $part -> setMaterial($material);
        $part -> setDo($todow);
        $part -> setWorkid($workid);
        $part -> setCreatedfor($createdfor);

            $this -> emi ->persist($work);
            $this -> emi ->persist($part);
            $this -> emi ->flush();

               return $this -> response -> setContent(json_encode(['saved'=>'ok','timer'=>$sumTime]));
    }


    /**
     * @Route("/delete_part", name="delete_part")
     */
    public function deletePart(Request $request){
        $id_part = $request -> request -> get('id_part');
        $part = $this -> partsRepository ->findOneBy(['id'=>$id_part]);

        $workId = $part -> getWorkid();
        $work = $this -> workRepository -> findOneBy(['id'=>$workId]);
        $hoursWork = (int) $work -> getHours();
        $hoursPart = (int) $part -> getTime();
        $newWorkHours = $hoursWork - $hoursPart;
        $work -> setHours($newWorkHours);

        $this->emi->remove($part);
        $this->emi->persist($work);
        $this->emi->flush();
        return $this -> response -> setContent(json_encode(['deleted'=>'ok','hours'=>$newWorkHours]));
    }
}


// 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





















































