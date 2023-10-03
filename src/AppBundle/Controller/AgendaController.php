<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Form\AgendaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgendaController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $entries = $this->getDoctrine()->getRepository(Agenda::class)->findAll();

        return $this->render('Agenda/index.html.twig', [
            // 'form' => $form->createView(),
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

                $agenda->setName(ucfirst(strtolower($agenda->getName())));
                $agenda->setSurname(ucfirst(strtolower($agenda->getSurname())));

                $em = $this->getDoctrine()->getManager();
                $em->persist($agenda);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }

            return $this->render('Agenda/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    /**
     * @Route("/edit", name="edit_agenda")
     */
    public function editAction(Request $request)
        {
            $id = $request->query->get('id');

            $em = $this->getDoctrine()->getManager();
            $agenda = $em->getRepository(Agenda::class)->find($id);

            $form = $this->createForm(AgendaType::class, $agenda);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $agenda->setName(ucfirst(strtolower($agenda->getName())));
                $agenda->setSurname(ucfirst(strtolower($agenda->getSurname())));

                $em->flush();

                return $this->redirectToRoute('homepage');
            }
            return $this->render('Agenda/edit.html.twig', [
                'agenda' => $agenda,
                'form' => $form->createView(),
            ]);

        }

    /**
     * @Route("/delete", name="delete_agenda", methods={"POST"})
     */
        public function deleteAction(Request $request)
        {
            $id = $request->request->get('id'); // Nota: ora usiamo ->request invece di ->query
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
