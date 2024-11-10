<?php

namespace App\Controller;

use App\Form\AdminTeamFormType;
use App\Services\TeamService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminTeamController extends AbstractController
{
    private $em;
    private $teamService;
    private $req;
    private $valid;

    function __construct(EntityManagerInterface $em, TeamService $service, ValidatorInterface $valid)
    {
        $this->em = $em;
        $this->teamService = $service;
        $this->req = new Request();
        $this->valid = $valid;
    }

    #[Route('/admin/team', name: 'admin_team')]
    public function index(): Response
    {
        return $this->render('admin_team/index.html.twig', [
            'controller_name' => 'AdminTeamController',
        ]);
    }

    #[Route(path: '/admin/team/ajouter', name: 'admin_team_add')]
    public function createTeam(): Response 
    {
        $form = $this->initForm(true);

        return $this->render('admin_team/manager.html.twig', [
            'title' => "Créer une fiche équipier",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/team/modifier/{id}', name: 'admin_team_update')]
    public function updateTeam(int $id): Response
    {
        $form = $this->initForm(false, $id);

        return $this->render('admin_team/manager.html.twig', [
            'title' => "Modifier une fiche équipier",
            'form' => $form->createView(),
        ]);
    }

    function initForm(bool $new, int $id = null) {
        $team = ($new) ? $this->teamService->createTeam() : $this->teamService->updateTeam($id);

        $form = $this->createForm(AdminTeamFormType::class, $team);
        $form->handleRequest($this->req);

        try {
            //code...
            if ($form->isSubmitted() && $form->isValid()) { 
                // Ajout de l'image
                $file = $form->get('image')->getData();
                if ($file) {
                    $file->move('uploads/images/team', $file->getClientOriginalName());
                    $team->setImage($file->getClientOriginalName());
                } else {
                    $team->setImage("no-image.jpg");
                }
                
                // Ajout de la description
                $team->setDescription(htmlspecialchars($form->get('description')->getData()));
                
                // Ajout des diplômes
                $team->setDiplomes(htmlspecialchars($form->get('diplomes')->getData()));

                // Envoi des données
                $this->em->persist($team);
                $this->em->flush();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
        

        return $form;
    }
}
