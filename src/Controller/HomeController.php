<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use App\Repository\ProjectManager;
use App\Repository\MessageManager;
use App\Entity\Project;
use App\Entity\Message;

class HomeController extends DefaultControllerAbstract
{
    public function indexAction(): void
    {
        $projects = (new ProjectManager())->find();

        foreach ($projects as $value) {
            $formattedEntity = $this->formatTheEntity($value, ['chapo', 'content']);
            $formattedProject[] = (new Project())->hydrate($formattedEntity)->setId($value->getId());
        }

        //$flashMessage = '';
        //$classValue = 'text-danger';
        $_SESSION['classValue'] = 'text-danger';

        if ($this->isSubmited('contact')) {
            $formValues = $this->getFormValues('contact');

            if (
                $this->tokenCSRFIsValid($formValues['tokenContactForm'])
                && $this->formFieldsIsNotEmpty($formValues)
            ) {
                $messageEntity = (new Message())->hydrate($formValues)->setSentOn(date('Y-m-d H:i:s'));
                $messageRepository = (new MessageManager())->insert($messageEntity);

                if ("0" !== $messageRepository) {
                    $emailSent = $this->sentEmail($messageEntity);
                    //$_SESSION['flashMessage'] = $emailSent ? 'Votre message a bien été envoyé' : 'Une erreur est survenue, votre message n\'a pas pu être envoyer';
                    //$_SESSION['classValue'] = $emailSent ? 'text-success' : 'text-danger';
                }


                $_SESSION['flashMessage'] = "0" !== $messageRepository && $emailSent ? 'Votre message a bien été envoyé' : 'Une erreur est survenue, votre message n\'a pas pu être envoyer';
                $classValue = 'Votre message a bien été envoyé' === $_SESSION['flashMessage'] ? 'text-success' : 'text-danger';
            }
        }

        $this->generateTokenCSRF();
        $this->renderView(
            'home.html.twig',
            [
                'projects' => $formattedProject,
                'flashMessage' => $_SESSION['flashMessage'] ?? '',
                'classValue' => $_SESSION['classValue'] ?? ''
            ]
        );
    }

    private function sentEmail(Message $messageEntity): bool
    {
        $messageFormattedEntity = $this->formatTheEntity($messageEntity, ['message']);
        $message = '
            <html>
                <head>
                    <title>Nouveau message</title>
                </head>
                <body>
                    <p>Envoyé par ' . $messageEntity->getFullname(). '</p>
                    <p>' . $messageFormattedEntity['message'] . '</p>
                </body>
            </html>
           ';
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';

        return mail(
            'nicolasrellier@yahoo.fr',
            'Nouveau message envoyé depuis ton site',
            $message,
            implode("\r\n", $headers)
        );
    }
}
