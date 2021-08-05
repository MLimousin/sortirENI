<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])
            ->add('dateHeureDebut', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie : '
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => "Date limite d'inscription : "
            ])
            ->add('nbreInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places : '
            ])

            ->add('duree', DateIntervalType::class, [
                'label' => 'Durée (minutes) : ',
                'widget' => 'integer',
                'labels' => ['minutes'=>false],
                'with_minutes' => true,
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
            ])

            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'name',
                'choice_value' => function (?Campus $campus) {
                    return $campus ? $campus->getId() : '';
                },
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville : ',
                'class' => Ville::class,
                'mapped' => false,
                'choice_label' => 'nom',
                'choice_value' => function (?Ville $ville) {
                    return $ville ? $ville->getId() : '';
                },
            ])
            ->add('lieu', EntityType::class, [
                'label' => 'Lieu : ',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'choice_value' => function (?Lieu $lieu) {
                    return $lieu ? $lieu->getId() : '';
                },
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue : ',
                'mapped' => false,
            ])
            ->add('codePostal', IntegerType::class, [
                'label' => 'Code Postal : ',
                'mapped' => false,
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude : ',
                'mapped' => false,
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude : ',
                'mapped' => false,
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
