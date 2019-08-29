<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom de la sortie',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('dateDebut',DateTimeType::class,[
                'label'=>'Date et heure de sortie'

            ])
            ->add('dateCloture',DateTimeType::class,[
                'label'=>'Date limite d\'inscription'
            ])
            ->add('nbInscriptionsMax',IntegerType::class,[
                'label'=>'Nombre de places',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('duree',DateType::class,[
                'label'=>'Durée en minutes'
            ])
            ->add('descriptionInfos',TextareaType::class,[
                'label'=>'Descriptionet infos',
                'trim'=>true,
                'attr'=> array('class'=>'form-control')
            ])

            ->add('organisateur',EntityType::class,[
                'class'=>User::class,
                'label'=>'Organisateur',
                'trim'=>true,
                'attr'=> array('class'=>'form-control')
            ])
            ->add('site',EntityType::class,[
                'class'=>Site::class,
                'choice_label'=>'nom',
                'label'=>'Organisme',
                'trim'=>true,
                'attr'=> array('class'=>'form-control')
            ])

            ->add('lieu',EntityType::class,[
                'class'=>Lieu::class,
                'choice_label'=>'nomLieu',
                'label'=>'Lieu',
                'trim'=>true,
                'attr'=> array('class'=>'form-control')
            ])
            ->add('submit', SubmitType::class, [
                'attr'=> array('class'=>'bouton'),
                "label" => "Enregistrer",]);

    }





    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
