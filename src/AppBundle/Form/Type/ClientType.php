<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class ClientType extends AbstractType
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
                'first_options'  => array('label' => 'form.email'),
                'second_options' => array('label' => 'form.confirmEmail')
            ))
            ->add('date', DateTimeType::class)
            ->add('dateReservation', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'form.dateReservation',
                'format' => 'dd/MM/yyyy',
                'required' => true,
                'attr' => array('readonly' => 'readonly', 'onchange' => 'verif14h()'),
            ))
            ->add('uniquId')
            ->add('nbrTicket')
            ->add('typeTicket', ChoiceType::class, array(
                'choices' => array(
                    'form.radiotypeTicket1' => Client::TYPE_DAY,
                    'form.radiotypeTicket2' => Client::TYPE_HALF_DAY,
                ),
                'label' => 'form.typeTicket',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ))
            ->add('prixTotal')
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
