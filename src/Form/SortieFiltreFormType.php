<?php

namespace App\Form;

use App\Entity\Campus;
use App\Filtre\SortieFiltre;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFiltreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'required' => false,
                'class' => Campus::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'empty_data' => null,
                'mapped' => false,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name');
                },
            ])
            ->add('recherche', SearchType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateMin', DateTimeType::class, [
                'label' => 'Entre ' ,
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('dateMax', DateTimeType::class, [
                'label' => 'et ',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortieFiltre::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
