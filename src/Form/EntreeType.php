<?php

namespace App\Form;

use App\Entity\Entree;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite', NumberType::class, array('label'=>'Quantité acheter','attr'=>array('class'=>'form-control form-group')))
            ->add('prix', NumberType::class, array('label'=>'Total en Francs CFA', 'attr'=>array('class'=>'form-control form-group')))
            ->add('date', DateType::class, array('label'=>'Date d\'achat','attr'=>array('class'=>'form-control form-group')))
            ->add('produit', EntityType::class, array('class'=>Produit::class, 'label'=>'Produit', 'attr'=>array('class'=>'form-control form-group')))
            ->add('Ajouter', SubmitType::class, array('label'=>'Ajouter', 'attr'=>array('class'=>'btn btn-outline-primary mt-3')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entree::class,
        ]);
    }
}
