<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AgendaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('phoneNumber')
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Maschio' => 'Maschio',
                    'Femmina' => 'Femmina'
                ],
                'placeholder' => 'Seleziona il sesso',
                'required' => true,
            ])
            ->add('address')
            ->add('fotoFilename', FileType::class, [
                'label' => 'Foto (file immagine)',
                'mapped' => false,  // Imposto a false poiché gestisco upload manualmente
                'required' => false
            ]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Agenda'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_agenda';
    }
}
