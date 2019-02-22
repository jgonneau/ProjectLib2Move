<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand')
            ->add('model')
            ->add('serialNumber')
            ->add('color')
            ->add('numberplate')
            ->add('kilometers')
            ->add('dateOfPurchase')
            ->add('buyingPrice')
            ->add('createdAt')
            ->add('createdBy')
            ->add('modifiedAt')
            ->add('modifiedBy')
            ->add('picture', FileType::class, array('mapped' => false))
            ->add('gallery')
            ->add('typeOfVehicle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
