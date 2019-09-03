<?php


namespace App\Form;


use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieRechercheType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('Site',EntityType::class,[
                'class'=>Site::class,
                'label'=>'Site',
                'trim'=>true,
                'attr'=> array('class'=>'form-control'),
                'required'=>false
            ])
            ->add('search',SearchType::class,[
                'label' => ' Le nom de la sortie contient :',
                'attr'=> array('class'=>'form-control mr-sm-2', 'placeholder'=>'Recherche'),
                'required'=>false
            ])
            ->add('dateDebut',DateType::class,[
                'label' => ' Entre  ',
                'widget'=>'single_text',
                'required'=>false,
                'attr'=> array('class'=>'form-control'),
            ])
            ->add('dateFin',DateType::class,[
                'label' => ' et ',
                'widget'=>'single_text',
                'required'=>false,
                'attr'=> array('class'=>'form-control')
            ])

            ->add('userOrgan', CheckboxType::class, [
                'label'    => 'Sorties dont je suis l\'organisateur',
                'required' => false,
            ])
            ->add('userInscris', CheckboxType::class, [
                'label'    => 'Sorties auquelles je suis inscris',
                'required' => false,
            ])
            ->add('userNonInscris', CheckboxType::class, [
                'label'    => 'Sorties auquelles je ne suis pas inscris',
                'required' => false,
            ])
            ->add('sortiePassee', CheckboxType::class, [
                'label'    => 'Sorties passées',
                'required' => false,
            ])



            ->add('submit', SubmitType::class, [
                'attr'=> array('class'=>'bouton'),
                "label" => "Rechercher"]);

    }





    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false
        ]);
    }
}