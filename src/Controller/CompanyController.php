<?php

namespace App\Controller;

use App\ApiResource\CompanyApiResource;
use App\Constant\UserRoles;
use App\Entity\Company;
use App\Service\ApiResponseFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/v1')]
class CompanyController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ApiResponseFormatter $responseFormatter,
        private CompanyApiResource   $companyApiResource

    )
    {

    }
    #[Route('/companies', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getCompanies()
    {
        $user = $this->getUser();

        if(in_array($user->getRole(),[UserRoles::ROLE_COMPANY_ADMIN,UserRoles::ROLE_SUPER_ADMIN])){
            $companies = $this->entityManager->getRepository(Company::class)->findAll();
            return $this->responseFormatter->success($this->companyApiResource->transformCollection($companies));
         }

        return $this->responseFormatter->error('You don\'t have permission to access',403);

    }

    #[Route('/companies', methods: ['POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function createCompany(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $company = new Company();
        $company->setName($data['name']);

        $entityManager->persist($company);
        $entityManager->flush();

        return $this->responseFormatter->success($this->companyApiResource->transform($company),'',201);
    }

}
