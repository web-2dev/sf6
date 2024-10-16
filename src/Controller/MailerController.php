<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function sendEmail(MailerInterface $mailer)
    {
      $email = (new Email())
      ->from('hello@example.com')
      ->to('gerardmentor@yopmail.com')
      //->cc('cc@example.com')
      //->bcc('bcc@example.com')
      //->replyTo('fabien@example.com')
      //->priority(Email::PRIORITY_HIGH)
      ->subject('Time for Symfony Mailer!')
      ->text('Sending emails is fun again!')
      ->html('<p>See Twig integration for better HTML integration!</p>');

      $mailer->send($email);

      $this->addFlash("success", "E-mail envoyé");
      return $this->redirectToRoute("app_home");
    }
}