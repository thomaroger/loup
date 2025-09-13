<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Child;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ParentRegisterType;
use Doctrine\ORM\EntityManagerInterface;



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

    #[Route('/register', name: 'app_parent_register')]
    public function register( Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher ): Response {
        
        $error = "";
        $user = new User();
        $form = $this->createForm(ParentRegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existing = $em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existing) {
                $error = "Cet email est déjà utilisé.";
                return $this->render('security/parent_register.html.twig', [
                    'form' => $form->createView(),
                    'error' => $error
                ]);
            }

            $existing = $em->getRepository(Child::class)->findOneBy(['firstName' => $form->get('childFirstName')->getData()]);
            if ($existing) {
                $error = "Cet enfant est déjà inscrit.";
                return $this->render('security/parent_register.html.twig', [
                    'form' => $form->createView(),
                    'error' => $error
                ]);
            }


            // Role parent
            $user->setRoles(['ROLE_PARENT']);

            // mot de passe vide ou généré automatiquement (ici on met juste l'email comme hashé)
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getEmail())
            );

            $em->persist($user);

            // Créer l'enfant lié
            $child = new Child();
            $child->setFirstName($form->get('childFirstName')->getData());
            $child->setParent($user);

            $em->persist($child);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie !');

            return $this->redirectToRoute('app_light_login');
        }

        return $this->render('security/parent_register.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }





    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
