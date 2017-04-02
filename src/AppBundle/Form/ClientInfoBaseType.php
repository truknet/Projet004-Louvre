<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class ClientInfoBaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', RepeatedType::class, array(
                'type' => EmailType::class,
                'required' => true,
                'first_options'  => array('label' => 'Email'),
                'second_options' => array('label' => 'Confirmer Email')
            ))
            ->add('dateReservation', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => true,
                'attr' => array('readonly' => 'readonly', 'onchange' => 'verif14h()'),
            ))
            ->add('nbrTicket', IntegerType::class, array(
                'required' => true,
            ))

            ->add('typeTicket', ChoiceType::class, array(
                'choices' => array(
                    'Ticket journée' => 'Journée',
                    'Ticket demi-journée' => 'Demi-journée',
                ),
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'choice_attr' => array(
                    'Ticket journée' => null,
                    'Ticket demi-journée' => null
                ),

            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }


}
