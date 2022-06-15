<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class,array('label'=>'Libellé','attr'=>array('class'=>'form-control form-group')))
            ->add('stock', NumberType::class, array('label'=>'Quantité en stock', 'attr'=>array('class'=>'form-control form-group')))
            ->add('categorie', EntityType::class, array('class'=>Categorie::class, 'label'=>'Produit', 'attr'=>array('class'=>'form-control form-group')))
            ->add('Ajouter', SubmitType::class, array('label'=>'Ajouter', 'attr'=>array('class'=>'btn btn-outline-primary mt-3')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
