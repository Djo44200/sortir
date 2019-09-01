<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomLieu', TextType::class,[
                'label'=>'Nom du lieu',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('rue', TextType::class,[
                'label'=>'Rue',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('latitude', NumberType::class,[
                'label'=>'Latitude',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('longitude', NumberType::class,[
                'label'=>'Longitude',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('ville', EntityType::class,
                ['class' => Ville::class,
                    'choice_label'=>'nom',
                    'label'=>'Ville',
                    'attr'=> array('class'=>'form-control')])
            ->add('submit','Submit')

            ->add('submit',SubmitType::class,[
                'label'=> 'Enregistrer',
                'attr' => array('class' => 'btn bouton')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
