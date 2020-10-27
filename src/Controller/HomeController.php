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
        $formattedProject = $this->getProjects($projects);

        $emailSent = false;

        $formValues = array_map('trim', $this->getFormValues('contact'));

        if (
            $this->isSubmited('contact')
            && $this->tokenCSRFIsValid($formValues['tokenContactForm'])
            && $this->formFieldsIsNotEmpty($formValues)
        ) {
            $messageEntity = (new Message())->hydrate($formValues)->setSentOn(date('Y-m-d H:i:s'));
            $messageRepository = (new MessageManager())->insert($messageEntity);
            $emailSent = $this->sentEmail($messageRepository, $messageEntity);
        }

        $this->generateTokenCSRF();
        $this->renderView(
            'home.html.twig',
            [
                'projects' => $formattedProject,
                'flashMessage' => $_SESSION['flashMessage'] ?? '',
                'classValue' => $_SESSION['classValue'] ?? '',
                'contact' => $emailSent ? '' : $formValues
            ]
        );
    }

    private function generateSessionVariable(string $messageRepository, bool $emailSent, array $formValues): void
    {
        $_SESSION['flashMessage'] = "0" !== $messageRepository && $emailSent ? 'Votre message a bien été envoyé' : 'Une erreur est survenue, votre message n\'a pas pu être envoyer';
        $_SESSION['classValue'] = 'Votre message a bien été envoyé' === $_SESSION['flashMessage'] ? 'text-success' : 'text-danger';
        $_SESSION['contact'] = $formValues;
    }

    private function getProjects(array $projects): array
    {
        $formattedProject = [];

        foreach ($projects as $project) {
            $formattedEntity = $this->formatTheEntity($project, ['chapo', 'content']);
            $formattedProject[] = (new Project())->hydrate($formattedEntity)->setId($project->getId());
        }

        return $formattedProject;
    }

    private function sentEmail(string $messageRepository, Message $messageEntity): bool
    {
        $emailSent = false;

        if ("0" !== $messageRepository) {
            //var_dump($messageRepository); die;
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

            $emailSent = mail(
                'nicolasrellier@yahoo.fr',
                'Nouveau message envoyé depuis ton site',
                $message,
                implode("\r\n", $headers)
            );
        }

        $this->generateSessionVariable($messageRepository, $emailSent, $this->getFormValues('contact'));

        return $emailSent;

    }
}
