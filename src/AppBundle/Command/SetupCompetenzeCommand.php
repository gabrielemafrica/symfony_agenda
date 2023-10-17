<?php

namespace AppBundle\Command; //nome bundle

use AppBundle\Entity\Setup_competenze;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


// nome classe come nome command
class SetupCompetenzeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        // nome per richiamare comando
            ->setName('import:setup:competenze')
            // iposto un oparametro obbligatorio
            ->addArgument('parametro_prova_obbligatorio', InputArgument::REQUIRED, 'parametro prova obbligatorio')
            // imposto un parametro opzionale
            ->addArgument('parametro_prova_opzionale', InputArgument::OPTIONAL, 'parametro prova opzionale')
        // descrizione comando
            ->setDescription('Prima importazione delle competenze');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $parametro_prova_obbligatorio = $input->getArgument('parametro_prova_obbligatorio');
        // $parametro_prova_opzionale = $input->getArgument('parametro_prova_opzionale');
        // $output->writeln("Prova");
        // $output->writeln($parametro_prova_obbligatorio);
        // $output->writeln($parametro_prova_opzionale);

        $em = $this->getContainer()->get('doctrine')->getManager();

        if ($parametro_prova_obbligatorio === '1') {
            $list_competence=array(
                // "php",
                "php",
                "symfony",
                "javascript",
                "html",
                "css",
                "mysql",
                "git"
            );
            foreach ($list_competence as $single_competence){
                // $output->writeln($single_competence);
                $verify_competence = $em->getRepository("AppBundle:Setup_competenze")->findOneBy(["description" => $single_competence]);

                if (!$verify_competence) {
                    $setup_competenze = new Setup_competenze();
                    $setup_competenze->setDescription($single_competence);
                    $em->persist($setup_competenze);
                }
            }
            $em->flush();
            $output->writeln("import eseguito correttamente!");
        }

        if ($parametro_prova_obbligatorio === '2') {
            
            $verify_competence = $em->getRepository("AppBundle:Setup_competenze")->find(10);
            if ($verify_competence) {
                // dump("ricerca con find: " . $verify_competence->getDescription());
                dump("ricerca con find: ");
                dump($verify_competence);
            }

            $verify_competence_all = $em->getRepository("AppBundle:Setup_competenze")->findAll();

            dump("ricerca con findAll");
            dump($verify_competence_all);

            $verify_competence_findOne = $em->getRepository("AppBundle:Setup_competenze")->findBy([
                "id" => array(9,10),
                "description" => array("php",)
            ]);

            dump("ricerca con findOne");
            dump($verify_competence_findOne);
        }
       
    }
}