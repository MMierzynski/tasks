<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 13.01.18
 * Time: 14:00
 */

namespace AppBundle\Form;


use AppBundle\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label'=>false,
                    'attr'=>['placeholder'=>'Title']
                ])
            ->add(
                'expiresAt',
                DateTimeType::class,
                [
                    'label'=>false,
                    'attr'=>['placeholder'=>'Expiry date'],
                    'data'=> new \DateTime('+ 1 hour')
                ])
            ->add(
                'description',
                TextareaType::class,
                [
                    'label'=>false,
                    'attr'=>['placeholder'=>'Description']
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['class_name'=>Task::class]);
    }

}