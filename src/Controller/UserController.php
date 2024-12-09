<?php

namespace App\Controller;

use App\ApiResource\UserApiResource;
use App\Entity\Company;
use App\Entity\User;
use App\Service\ApiResponseFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/v1')]
class UserController extends AbstractController
{


    public function __construct(
        private EntityManagerInterface $entityManager,
        private ApiResponseFormatter $responseFormatter,
        private UserApiResource  $userApiResource

    )
    {

    }


    #[Route('/users', name: 'all' ,methods: ['GET'] )]
    public function all(): JsonResponse
    {
        $user = $this->getUser();

        if ($user->getRole() === 'ROLE_SUPER_ADMIN') {
            $users = $this->entityManager->getRepository(User::class)->findAll();
            return $this->responseFormatter->success($this->userApiResource->transformCollection($users));
        }
        return $this->responseFormatter->error('You don\'t have permission to access',403);
    }

    #[Route('/users', methods: ['POST'])]
    #[IsGranted('ROLE_COMPANY_ADMIN', 'ROLE_SUPER_ADMIN')]
    public function createUser(Request $request,ValidatorInterface $validator,UserPasswordHasherInterface $hasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $newUser = new User();
        $newUser->setName($data['name']);
        $newUser->setEmail($data['email']);
        $newUser->setPassword($hasher->hashPassword($newUser, $data['password']));
        $newUser->setRole($data['role']);
        $company = $this->entityManager->getRepository(Company::class)->find($data['company_id']);

        if(!$company){

            return $this->responseFormatter->error('company not exist',404);
        }
        $newUser->setCompany($company);


        $errors = $validator->validate($newUser);

        if (count($errors) > 0) {
            return $this->responseFormatter->error('',400,$errors);
        }

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        return $this->responseFormatter->success($this->userApiResource->transform($newUser),'',201);

    }

    #[Route('/users/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function deleteUser(int $id): JsonResponse
    {
        $user =  $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->responseFormatter->error( 'User not found',404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->responseFormatter->success([],'User deleted');
    }


}
