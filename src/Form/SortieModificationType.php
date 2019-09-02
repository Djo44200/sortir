<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom de la sortie',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('dateDebut',DateTimeType::class,[
                'label'=>'Date et heure de sortie',
                'widget' => 'single_text',
                'attr' => array(
                    'min' => (new \DateTime('now'))->format('d/m/Y H:i')
                )
                
            ])
            ->add('dateCloture',DateType::class,[
                'label'=>'Date limite d\'inscription',
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax',IntegerType::class,[
                'label'=>'Nombre de places',
                'attr'=> array('class'=>'form-control')
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée en minutes'
            ])
            ->add('descriptionInfos',TextareaType::class,[
                'label'=>'Description et informations',
                'trim'=>true,
                'attr'=> array('class'=>'form-control')
            ])
            ->add('lieu',EntityType::class,[
                'class'=>Lieu::class,
                'choice_label'=>'nomLieu',
                'label'=>'Lieu',
                'trim'=>true,
                'attr'=> array('class'=>'form-control'),
                'required' => false
            ])
            ->add('ville', EntityType::class, [
                'class'=>Ville::class,
                'mapped' => false,
                'required' => false
            ])
            ->add('modifier', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => array('class' => 'btn bouton')
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class
        ]);
    }
}
