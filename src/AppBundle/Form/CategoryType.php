<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 13.01.18
 * Time: 14:29
 */

namespace AppBundle\Form;


use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => false,'attr' => ['placeholder'=>'Dodaj kategorie']])
            ->add('submit', SubmitType::class, ['label' => 'Dodaj']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['class_name' => Category::class]);
    }

}