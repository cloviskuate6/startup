<?php

namespace App\Form;

use App\Entity\Visite;
use App\Entity\Guide;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo')
            ->add('pays')
            ->add('lieu')
            ->add('date', DateType::class, ['widget' => 'single_text'])
            ->add('heureDebut')
            ->add('duree', IntegerType::class)
            ->add('commentaire')
            ->add('guide', EntityType::class, [
                'class' => Guide::class,
                'choice_label' => fn($guide) => $guide->getPrenom().' '.$guide->getNom(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}
