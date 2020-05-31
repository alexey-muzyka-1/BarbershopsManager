<?php

declare(strict_types=1);

namespace App\Controller\Barbershop;

use App\Annotation\Guid;
use App\Controller\ErrorHandler;
use App\Model\Barbershop\Entity\Company\Company;
use App\Model\Barbershop\UseCase\Company\Create;
use App\Model\Barbershop\UseCase\Company\Edit;
use App\Model\Barbershop\UseCase\Company\Remove;
use App\ReadModel\Barbershop\CompanyFetcher;
use DomainException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("barbershop_info/companies", name="barbershop_info.companies")
 */
class CompanyController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     */
    public function index(CompanyFetcher $companyFetcher): Response
    {
        $companies = $companyFetcher->all();

        return $this->render('app/barbershop/company/index.html.twig', compact('companies'));
    }

    /**
     * @Route("/create", name=".create")
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_COMPANIES")
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('barbershop_info.companies');
            } catch (DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/barbershop/company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name=".edit")
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_COMPANIES")
     */
    public function edit(Company $company, Request $request, Edit\Handler $handler): Response
    {
        $command = Edit\Command::fromCompany($company);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('barbershop_info.companies');
            } catch (DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/barbershop/company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name=".delete")
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_COMPANIES")
     */
    public function delete(Company $company, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('barbershop_info.companies.show', ['id' => $company->getId()]);
        }
        $command = new Remove\Command($company->getId()->getValue());

        try {
            $handler->handle($command);

            return $this->redirectToRoute('barbershop_info.companies');
        } catch (DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('barbershop_info.companies.show');
    }

    /**
     * @Route("/{id}", name=".show", requirements={"id"=Guid::PATTERN})
     */
    public function show(): Response
    {
        return $this->redirectToRoute('barbershop_info.companies');
    }
}
