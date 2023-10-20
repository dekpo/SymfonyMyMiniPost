<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(MailerInterface $mailerInterface): Response
    {
        $email = (new Email())
        ->from(new Address('mailtrap@example.com', 'Mailtrap'))
	    ->to('newuser@example.com')
	    ->cc('mailtrapqa@example.com')
	    ->addCc('staging@example.com')
	    ->bcc('mailtrapdev@example.com')
	    ->replyTo('mailtrap@example.com')
	    ->subject('Best practices of building HTML emails')
 	    ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
        ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailerInterface->send($email);
        
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
