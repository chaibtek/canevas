<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VilleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('codePostal')
            ->add('population')
            ->add('img')
            ->add('slug')
            ->add(
                'departement',
                EntityType::class,
                [
                    'class' => Departement::class,
                    'choice_label' => 'nom',
                    'placeholder' => 'choisir un département',
                    'label' => 'Département',
                ]
            )
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
