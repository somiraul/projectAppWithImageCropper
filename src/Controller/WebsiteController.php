<?php

namespace App\Controller;

use App\Entity\TestImages;
use App\Entity\User;
use App\Entity\Tasks;
use App\Service\ImageManagementService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WebsiteController extends AbstractController
{

    private $passwordEncoder;
    private $imageManager;
    private $testArray = [];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ImageManagementService $imageManager)
    {
            $this->testArray['passwordEncoder'] = $this->passwordEncoder = $passwordEncoder;
            $this->testArray['imageManager'] = $this->imageManager = $imageManager;
            return $this->testArray;
    }

    public function index()
    {
        return $this->render('website/index.html.twig');
    }

    public function register(Request $request)
    {

        $data = $request->request->all();
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->getProfilePicture();
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setEmail($data['email']);
        $user->setPassword($this->testArray['passwordEncoder']->encodePassword($user, $data['password']));

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response("Successfully added with the Id of " . $user->getId());
    }


    public  function profile()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $tasks = $this->getDoctrine()->getRepository(Tasks::class)->findBy(['userId' => $userId]);

        return $this->render('website/profile/userId.html.twig', ['tasks' => $tasks]);
    }

    public function addProjectView()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        return $this->render('website/profile/AddProject.html.twig', ['userId' => $userId]);
    }

    public function addProject(Request $request)
    {
        $data = $request->request->all();
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Tasks();
        $task->setUserId($data['userId']);
        $task->setProjectName($data['projectName']);
        $task->setStartDate(new \DateTime($data['startDate']));
        $task->setEndDate(new \DateTime($data['endDate']));

        $entityManager->persist($task);
        $entityManager->flush();

        return new Response("Successfully added with the Id of " . $task->getId());
    }

    public  function editProject($TaskId)
    {
        return $this->render('website/profile/editProject.html.twig');
    }

    public function imageCropperView()
    {
        return $this->render('website/ImageCropper.html.twig');
    }

    public function imageCropperSubmit(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $file = new TestImages();
        $image = $request->files->get('croppedImage');
        list($width, $height) = getimagesize($image);
        $greatestCommonDivisor = gmp_gcd($width, $height);
        $ratioFirstValue = gmp_intval($width) / gmp_intval($greatestCommonDivisor);
        $ratioSecondValue = gmp_intval($height) / gmp_intval($greatestCommonDivisor);
        $ratio = gmp_intval($ratioFirstValue) . ':' . gmp_intval($ratioSecondValue);
        $imageSizes = $this->generateImageSizes($image, $ratio);

//        $entityManager->persist($file);
//        $entityManager->flush();

        return new Response('TestResponse');

    }

    public function generateImageSizes(UploadedFile $file, $ratio) {

        if($ratio == '16:9'){
            $sizes =   array (
                "small"  => array(500,280),
                "medium" => array(750,420),
                "large"  => array(1500,840)
            );


            foreach ($sizes as $key => list($w, $h)) {
                $this->testArray['imageManager']->resize_image($file, $w, $h, $key);
            }
        } elseif($ratio == '4:3'){
            $sizes =   array (
                "small"  => array(300,225),
                "medium" => array(700,525),
                "large"  => array(1400,1050)
            );

            foreach ($sizes as $key => list($w, $h)) {
                $this->testArray['imageManager']->resize_image($file, $w, $h, $key);
            }
        } else {
            list($width, $height) = getimagesize($file);

            $this->testArray['imageManager']->resize_image($file, $width, $height, 'large');
            $this->testArray['imageManager']->resize_image($file, $width/2, $height/2, 'medium');
            $this->testArray['imageManager']->resize_image($file, $width/3, $height/3, 'small');

        }

    }
}
