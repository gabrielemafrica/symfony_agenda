<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Entity\Chiamate;
use AppBundle\Form\AgendaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;


class AgendaController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $entries = $this->getDoctrine()->getRepository(Agenda::class)->findBy(['deleted' => false]);

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
     * @Route("/editCall", name="editCall_agenda")
     */
    public function editCallAction(Request $request)
    {
        $id = $request->query->get('id'); 

        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository(Agenda::class)->find($id);

        // prendo le chiamte
        $chiamate = $entry->getChiamate();

        $chiamateArray = [];
        foreach ($chiamate as $chiamata) {
            $chiamateArray[] = [
                'id' => $chiamata->getId(),
                'date' => $chiamata->getDate()->format('Y-m-d'),  // assuming date is a DateTime object
                'time' => $chiamata->getTime()->format('H:i:s'),  // assuming time is a DateTime object
                'note' => $chiamata->getNote()
            ];
        }

        if (!$entry) {
            return new JsonResponse(['status' => 'error', 'message' => 'Contatto non trovato']);
        }

        $package = new Package(new EmptyVersionStrategy());
        $imageUrl = $package->getUrl('uploads/fotos/' . $entry->getFotoFilename());

        return new JsonResponse([
            'id' => $entry->getId(),
            'name' => $entry->getName(),
            'surname' => $entry->getSurname(),
            'phone_number' => $entry->getPhoneNumber(),
            'address' => $entry->getAddress(),
            'sex'=> $entry->getSex(),
            'fotoFilename' => $imageUrl,
            'chiamate' => $chiamateArray,
        ]);
    }

    /**
     * @Route("/save", name="save_agenda", methods={"POST"})
     */
    public function saveAction(Request $request)
    {

        //get id
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $phone_number = $request->request->get('phone_number');
        $address = $request->request->get('address');
        $sex = $request->request->get('sex');

        $em = $this->getDoctrine()->getManager();
        $agenda = $em->getRepository(Agenda::class)->find($id);

        if (!$agenda) {
            $agenda = new Agenda();
        }

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
     * @Route("/save-chiamata", name="save_chiamata", methods={"POST"})
     */
    public function saveChiamataAction(Request $request)
    {
        var_dump($request->request->all());

        //get id
        $id = $request->request->get('id');
        $id_contatto = $request->request->get('id_contatto');
        $date = $request->request->get('date');
        $time = $request->request->get('time');
        $note = $request->request->get('note');

        $em = $this->getDoctrine()->getManager();
        $chiamata = $em->getRepository(Chiamate::class)->find($id);
        
        if (!$chiamata) {
            $agenda = $em->getRepository(Agenda::class)->find($id_contatto);
            $chiamata = new Chiamate();
            $chiamata->setAgenda($agenda);
        }
    
        $chiamata->setDate($date);
        $chiamata->setTime($time);
        $chiamata->setNote($note);
          
        $em->persist($chiamata);
        $em->flush();

        // return $this->redirectToRoute('homepage');
        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @Route("/delete", name="delete_agenda")
     */
        public function deleteAction(Request $request)
        {
            // $id = $request->request->get('id');
            $id = $request->query->get('id');
            error_log("ID: " . $id);
            $em = $this->getDoctrine()->getManager();
            $agenda = $em->getRepository(Agenda::class)->find($id);

            if ($agenda) {
                $agenda->setDeleted(true);
                $em->persist($agenda);
                $em->flush();
            }

            return $this->redirectToRoute('homepage');
        }
}
