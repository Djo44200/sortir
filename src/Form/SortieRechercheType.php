<?php


namespace App\Form;


use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                'attr'=> array('class'=>'form-control')
            ])
            ->add('search',SearchType::class,[
                'label' => ' Le nom de la sortie contient :',
                'attr'=> array('class'=>'form-control mr-sm-2', 'placeholder'=>'Recherche'),
                'required'=>false
            ])
            ->add('dateDebut',DateType::class,[
                'label' => ' Entre  ',
                'widget'=>'single_text',
                'required'=>false
            ])
            ->add('dateFin',DateType::class,[
                'label' => ' et ',
                'widget'=>'single_text',
                'required'=>false
            ])
            ->add('sortieOrganisateur',CheckboxType::class,[
                'trim'=>true,
                'label'=> 'Sortie dont je suis l\'organisateur',
                'attr'=> array('class'=>'form-check-label'),
                'required'=>false
            ])
            ->add('sortieInscris',CheckboxType::class,[
                'trim'=>true,
                'label'=> 'Sortie auquelles je suis inscris',
                'attr'=> array('class'=>'form-check-label'),
                'required'=>false
            ])
            ->add('sortieNonInscris',CheckboxType::class,[
                'trim'=>true,
                'label'=> 'Sortie auquelles je ne suis pas inscris',
                'attr'=> array('class'=>'form-check-label'),
                'required'=>false
            ])
            ->add('sortiePassees',CheckboxType::class,[
                'trim'=>true,
                'label'=> 'Sortie passées',
                'attr'=> array('class'=>'form-check-label'),
                'required'=>false
            ])



            ->add('submit', SubmitType::class, [
                'attr'=> array('class'=>'bouton'),
                "label" => "Rechercher"]);

    }





    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'mapped' => false
        ]);
    }
}