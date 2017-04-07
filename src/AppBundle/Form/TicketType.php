<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'form.name'
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'form.firstname'
            ))
            ->add('country', CountryType::class, array(
                'label' => 'form.country',
                'preferred_choices' => array('FR')
            ))
            ->add('birthday', BirthdayType::class, array(
                'label' => 'form.birthdate',
                'format' => 'dd MM yyyy',
            ))
            ->add('tarifReduit', CheckboxType::class, array(
                'required' => false,
                'label' => 'form.reducedPrice'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticket';
    }


}
