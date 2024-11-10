<?php

namespace App\Services;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
    private $em;
    private $teamRepo;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->teamRepo = $this->em->getRepository(Team::class);
    }

    public function getTeams() {
        $team = $this->teamRepo->findAll();
        return $team;
    }

    public function getTeam(int $id) {
        $team = $this->teamRepo->findOneBy(["id" => $id]);
        return $team;
    }

    public function createTeam() {
        $team = new Team();
        return $team;
    }

    public function updateTeam(int $id) {
        $team = $this->getTeam($id);
        return $team;
    }

    public function deleteTeam(int $id) {
        $team = $this->getTeam($id);
        $this->em->remove($team);
        $this->em->flush();
        return $team;
    }
}
