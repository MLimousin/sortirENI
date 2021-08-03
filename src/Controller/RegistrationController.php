<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\UtilisateurAuthenticator;
use App\Service\Telechargement\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             UserAuthenticatorInterface $authenticator,
                             UtilisateurAuthenticator $formAuthenticator,
                             FileUploader $fileUploader,
                             SluggerInterface $slugger
                                ): Response
    {
        $user = new Utilisateur();
        $user->setAdministrateur(false);
        $user->setActif(true);

        $user->setPhoto('inconnu.jpg');

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // TELECHARGEMENT IMAGE AVEC SERVICE
            /*
            /** @var UploadedFile $photoProfil */
            /*
             $photoProfil = $form->get('photoFile')->getData();
            if($photoProfil){
                $photoProfilName = $fileUploader->upload($photoProfil);
                $user->setPhoto($photoProfilName);
            }
            */

            //TELECHARGEMENT IMAGE EN DIRECT DU CONTROLLER
            /** @var UploadedFile $photoProfil */
            $photoProfil = $form->get('photoFile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photoProfil) {
                $originalFilename = pathinfo($photoProfil->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoProfil->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoProfil->move(
                        $this->getParameter('profil_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setPhoto($newFilename);
            }


            // encode the plain password
            $user->setRoles(['ROLE_USER']);

            $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return  $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request);
            // do anything else you need here, like send an email

            return $this->redirectToRoute('utilisateur_profil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
