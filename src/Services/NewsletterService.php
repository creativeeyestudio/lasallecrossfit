<?php
namespace App\Services;

use App\Form\NewsletterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class NewsletterService extends AbstractController
{
    private $request;

    function __construct()
    {
        $this->request = new Request();
    }

    function getNewsForm(){
        $form = $this->createForm(NewsletterFormType::class);
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
        }

        return $form;
    }    
}
