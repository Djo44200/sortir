<?php


namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder-> add ( 'img' , FileType :: class , [
            'label' => 'Photo (jpg, png)' ,
            'mapped' => false ,
            'required' => false ,
            'constraints' => [
                new File ([
                    'maxSize' => '1024k' ,
                    'mimeTypesMessage' => 'Merci de choisir une image valide !' ,
                ])
            ],
        ]);
        $builder->add('pseudo', TextType::class, [
            "error_bubbling" => true,
            "trim" => true,
            "label" => "Pseudo",
            "required" => false,
            'attr' => array('class' => 'form-control form-control-lg'),
        ]);
        $builder->add('prenom', TextType::class, [
            "error_bubbling" => true,
            "trim" => true,
            "label" => "Prénom",
            "required" => false,
            'attr' => array('class' => 'form-control form-control-lg'),
        ]);
        $builder->add('nom', TextType::class, [
            "error_bubbling" => true,
            "trim" => true,
            "label" => "Nom",
            "required" => false,
            'attr' => array('class' => 'form-control form-control-lg'),
        ]);
        $builder->add('telephone', TextType::class, [
            "error_bubbling" => true,
            "trim" => true,
            "label" => "Téléphone",
            "required" => false,
            'attr' => array('class' => 'form-control form-control-lg'),
        ]);
        $builder->add('email', EmailType::class, [
            "error_bubbling" => true,
            "trim" => true,
            "label" => "Email",
            "required" => false,
            'attr' => array('class' => 'form-control form-control-lg'),
        ]);
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent être identiques',
            'options' => ['attr' => ['class' => 'form-control form-control-lg']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmation mot de passe'],
        ]);
        $builder->add('villeRattachement',EntityType::class, [
        'class'=>Site::class,
        'choice_label'=>'nom',
        'label'=>'Organisme',
        'trim'=>true,
        'attr'=> array('class'=>'form-control')
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}