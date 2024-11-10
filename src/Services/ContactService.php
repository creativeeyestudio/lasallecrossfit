<?php

namespace App\Services;

use App\Entity\ContactForm;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    private $em;
    private $contactRepo;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->contactRepo = $this->em->getRepository(ContactForm::class);
    }

    public function getContact(int $id) {
        $contact = $this->contactRepo->findOneBy(['id' => $id]);
        return $contact;
    }

    public function createContact() {
        $contact = new ContactForm();
        return $contact;
    }

    public function updateContact(int $id) {
        $contact = $this->getContact($id);
        return $contact;
    }

    public function deleteUpdate(int $id) {
        $contact = $this->getContact($id);
        $this->em->remove($contact);
        $this->em->flush();
        return $contact;
    }
}
