<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Entity\Chiamate;
use AppBundle\Entity\Setup_competenze;
use AppBundle\Entity\AgendaCompetenze;
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
        //take competenze
        $competenze = $this->getDoctrine()->getRepository(Setup_competenze::class)->findBy(['deleted' => false]);
        //take chiamate
        $chiamate = $this->getDoctrine()->getRepository(Chiamate::class)->findAll();

        return $this->render('@AppBundle/Agenda/index.html.twig', [
            'entries' => $entries,
            'competenze' => $competenze,
            'chiamate' => $chiamate
        ]);
    }

    /**
     * @Route("/editCall", name="editCall_agenda")
     */
    public function editCallAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id'); 
        if (!$id) {
            // prendo tutte le competenze
            $total_competenze =  $em->getRepository(Setup_competenze::class)->findBy(['deleted' => false]);
            $totalCompetenzeArray = [];
            foreach ($total_competenze as $competenza) {
                $totalCompetenzeArray[] = [
                    'id' => $competenza->getId(),
                    'description' => $competenza->getDescription()
                ];
            };
            return new JsonResponse($totalCompetenzeArray);
        }
       
        $entry = $em->getRepository(Agenda::class)->find($id);
        
        // prendo le chiamte
        $chiamate = $entry->getChiamate();
        
        $chiamateArray = [];
        foreach ($chiamate as $chiamata) {
            $chiamateArray[] = [
                'id' => $chiamata->getId(),
                'id_contatto' => $chiamata->getIdContatto(),
                'date' => $chiamata->getDate()->format('Y-m-d'),  // assuming date is a DateTime object
                'time' => $chiamata->getTime()->format('H:i:s'),  // assuming time is a DateTime object
                'note' => $chiamata->getNote()
            ];
        }
        // prendo tutte le competenze
        $total_competenze =  $em->getRepository(Setup_competenze::class)->findBy(['deleted' => false]);
        $totalCompetenzeArray = [];
        foreach ($total_competenze as $competenza) {
            $totalCompetenzeArray[] = [
                'id' => $competenza->getId(),
                'description' => $competenza->getDescription()
            ];
        }

        //prendo le competenze associate
        $chose_competenze =  $em->getRepository(AgendaCompetenze::class)->findBy(['agenda' => $id]);
        $choseCompetenzeArray = [];
        if ($chose_competenze) {
            
            foreach ($chose_competenze as $competenza) {
                
                $choseCompetenzeArray[] = [
                    'id' => $competenza->getCompetenza()->getId()
                ];
            }
        }else {
            $choseCompetenzeArray = null;
        }


        if (!$entry) {
            return new JsonResponse(['status' => 'error', 'message' => 'Contatto non trovato']);
        }

        $package = new Package(new EmptyVersionStrategy());
        $imageUrl = $package->getUrl('uploads/fotos/' . $entry->getFotoFilename());
        if (!$entry->getFotoFilename()) {
            $imageUrl = $package->getUrl('uploads/fotos/no-img.webp');
        }

        return new JsonResponse([
            'id' => $entry->getId(),
            'name' => $entry->getName(),
            'surname' => $entry->getSurname(),
            'phone_number' => $entry->getPhoneNumber(),
            'address' => $entry->getAddress(),
            'sex'=> $entry->getSex(),
            'fotoFilename' => $imageUrl,
            'chiamate' => $chiamateArray,
            'total_competenze' => $totalCompetenzeArray,
            'chose_competenze' => $choseCompetenzeArray,
        ]);
    }

    /**
     * @Route("/save", name="save_agenda", methods={"POST"})
     */
    public function saveAction(Request $request)
    {
        function capitalizeWords($string) {
            return implode(' ', array_map('ucfirst', explode(' ', strtolower($string))));
        }

        // recupero dei dati dal request
        $id = $request->request->get('id');
        $name = capitalizeWords($request->request->get('name'));
        $surname = capitalizeWords($request->request->get('surname'));        
        $phone_number = $request->request->get('phone_number');
        $address = $request->request->get('address');
        $sex = $request->request->get('sex');

        $em = $this->getDoctrine()->getManager();
        $agenda = $em->getRepository(Agenda::class)->find($id);

        if (!$agenda) {
            $agenda = new Agenda();
        }

        // settaggio delle proprietà
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
            $newFilename = $originalFilename.'-'.uniqid().'.'.$fotoFile->guessExtension();

            // spostamento del file nel directory di destinazione
            $fotoFile->move(
                $this->getParameter('foto_directory'),
                $newFilename
            );

            // salvataggio del filename nel database
            $agenda->setFotoFilename($newFilename);
        }

        // Gestione delle competenze
        $competenzeData = $request->request->get('competenza');
         
        // rimuovi tutte le competenze associte
        if ($agenda->getId()) {
            foreach ($agenda->getAgendaCompetenze() as $competenzaAssociata) {
                // non usare
                // $agenda->removeAgendaCompetenze($competenzaAssociata);
                $em->remove($competenzaAssociata);
            }
        }
        if ($competenzeData && is_array($competenzeData)) {
            $competenzeRepo = $em->getRepository(Setup_competenze::class);

            foreach ($competenzeData as $competenzaId) {
                $competenza = $competenzeRepo->find($competenzaId);

                if ($competenza) {
                    $agendaCompetenza = new AgendaCompetenze();
                    $agendaCompetenza->setAgenda($agenda);
                    $agendaCompetenza->setCompetenza($competenza);
                    
                    // non usare
                    // $agenda->addAgendaCompetenze($agendaCompetenza); // associa l'entità AgendaCompetenze all'entità Agenda
        
                    $em->persist($agendaCompetenza); ;
                }
            }
        }

        // Se è una nuova agenda, salvala e termina la funzione
        if (!$agenda->getId()) {
            $em->persist($agenda);
            $em->flush();

            return new JsonResponse(['status' => 'success']);
        }

        // Di seguito, la logica per la gestione delle chiamate, che viene applicata solo se l'agenda esiste già.

        $chiamateData = $request->request->get('chiamate');

        if ($chiamateData && is_array($chiamateData)) {

            $chiamataRepo = $em->getRepository(Chiamate::class);

            foreach ($chiamateData as $chiamataData) {
                $chiamataId = isset($chiamataData['id']) ? $chiamataData['id'] : null;
                $chiamata = null;

                // Verifica se l'ID della chiamata esiste, e in tal caso, recuperare quella chiamata
                if ($chiamataId) {
                    $chiamata = $chiamataRepo->find($chiamataId);
                }

                // Se la chiamata con quell'ID non esiste, creiamo una nuova
                if (!$chiamata) {
                    $chiamata = new Chiamate();
                    $chiamata->setAgenda($agenda);
                }

                $chiamata->setDate(new \DateTime($chiamataData['date']));
                $chiamata->setTime(new \DateTime($chiamataData['time']));
                $chiamata->setNote($chiamataData['note']);

                // salvataggio della chiamata
                $em->persist($chiamata);
            }
        }


        // salvataggio delle entità
        $em->persist($agenda);
        $em->flush();



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

    /**
     * @Route("/deleteChiamata", name="delete_chiamata")
     */
    public function deleteChiamataAction(Request $request)
    {
        $id = $request->get('id');

        error_log("ID: " . $id);

        $em = $this->getDoctrine()->getManager();
        $chiamata = $em->getRepository(Chiamate::class)->find($id);

        // rimuovi la chiamata
        $em->remove($chiamata);
        $em->flush(); // esegui le modifiche sul database

        return new JsonResponse(['status' => 'success', 'message' => 'Chiamata eliminata con successo!']);
    }



    //*! competenze

    /**
     * @Route("/saveCompetenza", name="save_competenza", methods={"POST"})
     */
    public function saveCompetenzaAction(Request $request)
    {

        // recupero dei dati dal request
        $id = $request->request->get('idCompetenza');
        $description = $request->request->get('nameCompetenza');

        $em = $this->getDoctrine()->getManager();
        $competenza = $em->getRepository(Setup_competenze::class)->find($id);
        // $competenzaAgenda = $em->getRepository(AgendaCompetenze::class)->findBy(['competenza_id' => $id]);

        if (!$competenza) {
            $competenza = new Setup_competenze();
        }

        // settaggio delle proprietà
        $competenza->setDescription($description);

        // Gestione delle agende
        $agendaData = $request->request->get('agenda');
    
        // rimuovi tutte le competenze associte
        if ($competenza->getId()) {
            foreach ($competenza->getAgendaCompetenze() as $agendaAssociata) {
                // $competenza->removeAgendaCompetenze($agendaAssociata);
                $em->remove($agendaAssociata);
            }
        }
        if ($agendaData && is_array($agendaData)) {
            $agendaRepo = $em->getRepository(Agenda::class);

            foreach ($agendaData as $agendaId) {
                $agenda = $agendaRepo->find($agendaId);

                if ($agenda) {
                    $agendaCompetenza = new AgendaCompetenze();
                    $agendaCompetenza->setAgenda($agenda);
                    $agendaCompetenza->setCompetenza($competenza);
                    
                    $competenza->addAgendaCompetenze($agendaCompetenza); // associa l'entità AgendaCompetenze all'entità Competenza
        
                    $em->persist($agendaCompetenza); ;
                }
            }
        }


        $em->persist($competenza);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
        /**
     * @Route("/editCompetenza", name="edit_competenza")
     */
    public function editCompetenzaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');

        // prendo tutte le voci in agenda

        $total_agenda =  $em->getRepository(Agenda::class)->findBy(['deleted' => false]);
        $totalAgendaArray = [];
        foreach ($total_agenda as $agenda) {
            $totalAgendaArray[] = [
                'id' => $agenda->getId(),
                'name' => $agenda->getName(),
                'surname' => $agenda->getSurname()
            ];
        };
        if (!$id) {
            return new JsonResponse($totalAgendaArray);
        }
       
        $competenza = $em->getRepository(Setup_competenze::class)->find($id);

        //prendo le agende associate
        $agende_associate =  $em->getRepository(AgendaCompetenze::class)->findBy(['competenza' => $id]);
        $agendeAssociateArray = [];
        if ($agende_associate) {
            
            foreach ($agende_associate as $agenda) {
                
                $agendeAssociateArray[] = [
                    'id' => $agenda->getAgenda()->getId()
                ];
            }
        }else {
            $agendeAssociateArray = null;
        }


        if (!$competenza) {
            return new JsonResponse(['status' => 'error', 'message' => 'Competenza non trovata']);
        }

        return new JsonResponse([
            'id' => $competenza->getId(),
            'description' => $competenza->getDescription(),
            'total_agenda' => $totalAgendaArray,
            'agende_associate' => $agendeAssociateArray,
        ]);
    }

        /**
     * @Route("/deleteCompetenza", name="delete_competenza")
     */
    public function deleteCompetenzaAction(Request $request)
    {
        $id = $request->query->get('id');
        error_log("ID: " . $id);
        $em = $this->getDoctrine()->getManager();
        $competenza = $em->getRepository(Setup_competenze::class)->find($id);
        

        if ($competenza) {
            $agendeAssociate = $em->getRepository(AgendaCompetenze::class)->findBy(['competenza_id' => $id]);
            if ($agendeAssociate) {
                $errore = 'La competenza non può essere eliminata, è assegnata a delle persone. Devi prima disassociarla!';
                return new JsonResponse(['error' => $errore]);
            }
                $competenza->setDeleted(true);
                $em->persist($competenza);
                $em->flush();
                return new JsonResponse(['status' => 'success']);
        }
    }
}
