<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    #[Route(path: '/login/{email}', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, string $email = null): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if(!empty($email)) {
            $lastUsername = $email;
        }
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/light-login', name: 'app_light_login', methods: ['GET', 'POST'])]
    public function lightLogin(Request $request, UserRepository $userRepository, Security $security): Response
    {
        $error = "";
        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                $error = "Identifiants invalides";
            } else {
                if ($user->hasRole('ROLE_ADMIN')) {
                    return $this->redirectToRoute('app_login', array('email' => $user->getEmail()));
                }
                return $security->login($user, 'security.authenticator.form_login.main');
            }   
        }

        return $this->render('security/light.login.html.twig', [ 'error' => $error]);
    }




    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
