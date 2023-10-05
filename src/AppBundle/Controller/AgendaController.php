<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Form\AgendaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AgendaController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $entries = $this->getDoctrine()->getRepository(Agenda::class)->findAll();

        // form
        $agenda = new Agenda();
        $form = $this->createForm(AgendaType::class, $agenda);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // gestione immagine
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $fotoFile */
            $fotoFile = $form['fotoFilename']->getData();
            
            if ($fotoFile) {
                $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $fotoFile->guessExtension();

                $fotoFile->move(
                    $this->getParameter('foto_directory'),
                    $newFilename
                );

                $agenda->setFotoFilename($newFilename);
            }

            // gestisco maiuscole
            $agenda->setName(ucfirst(strtolower($agenda->getName())));
            $agenda->setSurname(ucfirst(strtolower($agenda->getSurname())));

            $em = $this->getDoctrine()->getManager();
            $em->persist($agenda);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@AppBundle/Agenda/index.html.twig', [
            'form' => $form->createView(),
            'entries' => $entries,
        ]);
    }

    /**
     * @Route("/show", name="show_agenda")
     */
    public function showAction(Request $request)
    {
        $id = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();
        $entries = $em->getRepository(Agenda::class)->find($id);

        return $this->render('@AppBundle/Agenda/show.html.twig', [
            'entries' => $entries,
        ]);
    }

    /**
     * @Route("/add", name="add_agenda")
     */
    public function addAction(Request $request)
        {
            $agenda = new Agenda();
            $form = $this->createForm(AgendaType::class, $agenda);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                // gestione immagine
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $fotoFile */
                $fotoFile = $form['fotoFilename']->getData();
                
                if ($fotoFile) {
                    $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // $safeFilename = hash('sha256', $originalFilename);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $fotoFile->guessExtension();


                    $fotoFile->move(
                        $this->getParameter('foto_directory'),
                        $newFilename
                    );

                    $agenda->setFotoFilename($newFilename);
                }

                $agenda->setName(ucfirst(strtolower($agenda->getName())));
                $agenda->setSurname(ucfirst(strtolower($agenda->getSurname())));

                $em = $this->getDoctrine()->getManager();
                $em->persist($agenda);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }

            return $this->render('@AppBundle/Agenda/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    /**
     * @Route("/add-modal", name="add_agenda_modal", methods={"POST"})
     */
    public function createAction(Request $request)
    {

        $agenda = new Agenda();
        
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $phone_number = $request->request->get('phone_number');
        $address = $request->request->get('address');
        $sex = $request->request->get('sex');

        $agenda->setName($name);
        $agenda->setSurname($surname);
        $agenda->setPhoneNumber($phone_number);
        $agenda->setAddress($address);
        $agenda->setSex($sex);

        // Gestione immagine
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $fotoFile */
        $fotoFile = $request->files->get('fotoFilename');
        
        if ($fotoFile) {
            $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $fotoFile->guessExtension();

            $fotoFile->move(
                $this->getParameter('foto_directory'),
                $newFilename
            );

            $agenda->setFotoFilename($newFilename);
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($agenda);
        $entityManager->flush();

        // return $this->redirectToRoute('homepage');
        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @Route("/edit", name="edit_agenda")
     */
    public function editAction(Request $request)
        {
            $id = $request->query->get('id');

            $em = $this->getDoctrine()->getManager();
            // dump($this->getDoctrine()->getManager());
            // die();
            $agenda = $em->getRepository(Agenda::class)->find($id);

            $form = $this->createForm(AgendaType::class, $agenda);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // gestione immagine
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $fotoFile */
                $fotoFile = $form['fotoFilename']->getData();

                if ($fotoFile) {
                    $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // $safeFilename = hash('sha256', $originalFilename);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $fotoFile->guessExtension();

                    $fotoFile->move(
                        $this->getParameter('foto_directory'),
                        $newFilename
                    );
        
                    $agenda->setFotoFilename($newFilename);
                }

                $agenda->setName(ucfirst(strtolower($agenda->getName())));
                $agenda->setSurname(ucfirst(strtolower($agenda->getSurname())));

                $em->flush();

                return $this->redirectToRoute('homepage');
            }
            return $this->render('@AppBundle/Agenda/edit.html.twig', [
                'agenda' => $agenda,
                'form' => $form->createView(),
            ]);

        }
    /**
     * @Route("/edit-modal", name="edit_agenda_modal")
     */
    public function editActionModal(Request $request)
        {
            //take id
            $id = $request->request->get('id');
            //search in database
            $em = $this->getDoctrine()->getManager();
            $agenda = $em->getRepository(Agenda::class)->find($id);
            
            //take data from request and put in entity
            $name = $request->request->get('name');
            $surname = $request->request->get('surname');
            $phone_number = $request->request->get('phone_number');
            $address = $request->request->get('address');
            $email = $request->request->get('email');
 
            $agenda->setName($name);
            $agenda->setSurname($surname);
            $agenda->setPhoneNumber($phone_number);
            $agenda->setAddress($address);
 
            // Gestione immagine
            // if are new image then save
            if ($request->files->get('fotoFilename')) {
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $fotoFile */
                $fotoFile = $request->files->get('fotoFilename');

                $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $fotoFile->guessExtension();

                $fotoFile->move(
                    $this->getParameter('foto_directory'),
                    $newFilename
                );

                $agenda->setFotoFilename($newFilename);
            }
            //update to db
            $agenda->setName(ucfirst(strtolower($agenda->getName())));
            $agenda->setSurname(ucfirst(strtolower($agenda->getSurname())));

            $em->flush();

            return $this->redirectToRoute('homepage');

        }

    /**
     * @Route("/delete", name="delete_agenda", methods={"POST"})
     */
        public function deleteAction(Request $request)
        {
            $id = $request->request->get('id'); // Nota: ora usiamo ->request invece di ->query
            // dump($id);
            // die();
            error_log("ID: " . $id);
            $em = $this->getDoctrine()->getManager();
            $agenda = $em->getRepository(Agenda::class)->find($id);

            if ($agenda) {
                $em->remove($agenda);
                $em->flush();
            }

            return $this->redirectToRoute('homepage');
        }
}
