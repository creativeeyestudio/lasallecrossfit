<?php

namespace App\Controller;

use App\Services\FormsService;
use App\Services\PagesService;
use App\Services\ContactService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebPagesIndexController extends AbstractController
{
    private $pages_services;
    private $formService;
    private $request;

    public function __construct(PagesService $pages_services, FormsService $formService)
    {
        $this->pages_services = $pages_services;
        $this->formService = $formService;
        $this->request = new Request();
    }
    
    // Index Page
    // -------------------------------------------------------------------------------------------
    #[Route('/{_locale}', name: 'web_index', requirements: ['_locale' => 'fr'])]
    public function index(): Response
    {
        return $this->pages_services->getMainPage();
    }

    // Other Page
    // -------------------------------------------------------------------------------------------
    #[Route('/{_locale}/{page_slug}', name: 'web_page', requirements: ['_locale' => 'fr'])]
    public function page(string $page_slug): Response
    {
        return $this->pages_services->getPage($page_slug);
    }
    
    // Post Page
    // -------------------------------------------------------------------------------------------
    #[Route('/{_locale}/blog/{post_slug}', name: 'web_post', requirements: ['_locale' => 'fr|en'])]
    public function post(string $post_slug): Response
    {
        return $this->pages_services->getPost($this->request, $post_slug);
    }

    // Redirections
    // -------------------------------------------------------------------------------------------
    #[Route('/', name: 'web_redirect')]
    public function redirectBase(){
        return $this->redirectToRoute('web_index');
    }

    // API
    // -------------------------------------------------------------------------------------------
    #[Route('/api/contact-form', name: 'contact_form', methods: ["POST"])]
    public function sendContactForm(EntityManagerInterface $em, Request $request, ContactService $contactService) {
        $data = json_decode($request->getContent(), true);

        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $tel = $data['tel'];
        $mail = $data['email'] ?? "example@xyz.fr";
        $message = $data['message'];
        $objet = $data['objet'];

        
        $dataArray = [
            'nom' => $nom,
            'prenom' => $prenom,
            'tel' => $tel,
            'mail' => $mail,
            'message' => $message,
            'objet' => $objet,
        ];

        try {
            // Envoi des e-mails
            $this->formService->send($mail, 'contact@lasallecrossfit.fr', $objet, 'form-e-mail', $dataArray);
            // $this->formService->send('no-reply@gym07.com', $dataArray['email'], "Gym 07 - Récapitulatif de votre demande", '', $dataArray);

            // Enregistrement du contact
            $contact = $contactService->createContact();
            $contact->setNom($data['nom']);
            $contact->setPrenom($data['prenom']);
            $contact->setTel($data['tel']);
            $contact->setEmail($data['email']);
            $contact->setObjet($data['objet']);
            $contact->setMessage($data['message']);

            $em->persist($contact);
            $em->flush();

            return new JsonResponse([
                "Response" => "Votre E-Mail a bien été envoyé"
            ], 200);
        } catch (\Throwable $th) {
            return $this->json([
                'Response' => $th,
                "data" => $data
            ], 500);
        }
    }
}
