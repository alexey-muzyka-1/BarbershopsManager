<?php

declare(strict_types=1);

namespace App\Controller\Barbershop;

use App\Annotation\Guid;
use App\Controller\ErrorHandler;
use App\Model\Barbershop\Entity\Barbershop\Barbershop;
use App\Model\Barbershop\UseCase\Barbershop\Activate;
use App\Model\Barbershop\UseCase\Barbershop\Archive;
use App\Model\Barbershop\UseCase\Barbershop\Create;
use App\Model\Barbershop\UseCase\Barbershop\Move;
use App\Model\Barbershop\UseCase\Barbershop\Edit;
use App\Model\Barbershop\UseCase\Barbershop\Remove;
use App\ReadModel\Barbershop\BarbershopFetcher;
use App\ReadModel\Barbershop\Filter;
use DomainException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/barbeshop_info/barbershops", name="barbershop_info.barbershops")
*/
class BarbershopController extends AbstractController
{
    private const DEFAULT_PER_PAGE = 10;
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     */
    public function index(Request $request, BarbershopFetcher $fetcher): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            $filter->perPage ? $filter->perPage : self::DEFAULT_PER_PAGE,
            $request->query->get('sort', 'name'),
            $request->query->get('direction', 'asc')
        );

        return $this->render('app/barbershop/barbershop/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name=".create")
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_BARBERSHOP")
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('barbershop_info.barbershops');
            } catch (DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/barbershop/barbershop/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name=".edit")
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_BARBERSHOP")
     */
    public function edit(Barbershop $barbershop, Request $request, Edit\Handler $handler): Response
    {
        $command = Edit\Command::fromBarbershop($barbershop);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
            } catch (DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/barbershop/barbershop/edit.html.twig', [
            'barbershop' => $barbershop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/remove", name=".remove", methods={"POST"})
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_BARBERSHOP")
     */
    public function remove(Barbershop $barbershop, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('remove', $request->request->get('token'))) {
            return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
        }

        $command = new Remove\Command($barbershop->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('barbershop_info.barbershops');
    }

    /**
     * @Route("/{id}/move", name=".move")
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_BARBERSHOP")
     */
    public function move(Barbershop $barbershop, Request $request, Move\Handler $handler): Response
    {
        $command = Move\Command::fromMember($barbershop);

        $form = $this->createForm(Move\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/barbershop/barbershop/move.html.twig', [
            'barbershop' => $barbershop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/archive", name=".archive", methods={"POST"})
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_BARBERSHOP")
     */
    public function archive(Barbershop $barbershop, Request $request, Archive\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('archive', $request->request->get('token'))) {
            return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
        }

        $command = new Archive\Command($barbershop->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
    }

    /**
     * @Route("/{id}/activate", name=".activate", methods={"POST"})
     * @IsGranted("ROLE_MANAGE_BARBERSHOP_BARBERSHOP")
     */
    public function reinstate(Barbershop $barbershop, Request $request, Activate\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('reinstate', $request->request->get('token'))) {
            return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
        }

        $command = new Activate\Command($barbershop->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('barbershop_info.barbershops.show', ['id' => $barbershop->getId()]);
    }

    /**
     * @Route("/{id}", name=".show", requirements={"id"=Guid::PATTERN})
     */
    public function show(Barbershop $barbershop): Response
    {
        return $this->render('app/barbershop/barbershop/show.html.twig', compact('barbershop'));
    }
}
