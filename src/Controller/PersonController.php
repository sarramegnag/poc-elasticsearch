<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\CreatePersonType;
use App\Model\Book;
use App\Repository\PersonRepository;
use JoliCode\Elastically\Messenger\IndexationRequest;
use JoliCode\Elastically\Messenger\MultipleIndexationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/update/{id}', name: 'app_person_update')]
    public function update(Person $person, Request $request, PersonRepository $personRepository, MessageBusInterface $bus): Response
    {
        $form = $this->createForm(CreatePersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->save($person, true);

            // Reindex author books
            $operations = [];
            foreach ($person->getBooks() as $book) {
                $operations[] = new IndexationRequest(Book::class, $book->getId());
            }
            $bus->dispatch(new MultipleIndexationRequest($operations));

            $this->addFlash('success', 'Author has been updated');

            return $this->redirectToRoute('app_book');
        }

        return $this->render('person/update.html.twig', [
            'form' => $form,
        ]);
    }
}
