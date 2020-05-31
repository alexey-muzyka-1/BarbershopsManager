<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\ErrorHandler;
use App\Model\User\UseCase\SignUp;
use App\Model\User\UserErrorConstants;
use App\Model\User\Constants\UserSuccesConstants;
use App\ReadModel\User\UserFetcher;
use App\Security\LoginFormAuthenticator;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SignUpController extends AbstractController
{
    private $users;
    private $errors;

    public function __construct(UserFetcher $users, ErrorHandler $errors)
    {
        $this->users = $users;
        $this->errors = $errors;
    }

    /**
     * @Route("/signup", name="auth.signup")
     *
     * @param Request $request
     * @param SignUp\Request\Handler $handler
     *
     * @return Response
     */
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = new SignUp\Request\Command();

        $form = $this->createForm(SignUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', UserSuccesConstants::USER_AWAITING_EMAIL_CONFIRMATION);
            } catch (DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/{token}", name="auth.signup.confirm")
     *
     * @param Request $request
     * @param string $token
     * @param SignUp\Confirm\Handler $handler
     * @param UserProviderInterface $userProvider
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     *
     * @return Response
     */
    public function confirm(
        Request $request,
        string $token,
        SignUp\Confirm\Handler $handler,
        UserProviderInterface $userProvider,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    ): Response {
        if (!$user = $this->users->findBySignUpConfirmToken($token)) {
            $this->addFlash('error', UserErrorConstants::USER_INCORRECT_CONFIRM_TOKEN);

            return $this->redirectToRoute('auth.signup');
        }

        $command = new SignUp\Confirm\Command($token);

        try {
            $handler->handle($command);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $userProvider->loadUserByUsername($user->email),
                $request,
                $authenticator,
                'main'
            );
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('auth.signup');
        }
    }
}