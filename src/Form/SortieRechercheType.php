<?php


namespace App\Form;


use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'attr'=> array('class'=>'form-control mr-sm-2', 'placeholder'=>'Recherche')
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