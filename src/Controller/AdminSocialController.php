<?php

namespace App\Controller;

use App\Entity\Social;
use App\Form\SocialAdminFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSocialController extends AbstractController
{
    #[Route('/admin/social', name: 'app_admin_social')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $socialData = $em->getRepository(Social::class)->find(1);
        if (!$socialData) {
            $socialData = new Social();
        }
        
        $form = $this->createForm(SocialAdminFormType::class, $socialData);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($socialData);
            $em->flush();
            return new RedirectResponse($this->generateUrl('app_admin_social'));
        }

        return $this->render('admin_social/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
