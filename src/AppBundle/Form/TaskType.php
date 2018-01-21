<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 13.01.18
 * Time: 14:00
 */

namespace AppBundle\Form;


;
use AppBundle\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label'=>false,
                    'attr'=>['placeholder'=>'Tytuł']
                ])
            ->add(
                'expiresAt',
                DateTimeType::class,
                [
                    'label'=>false,
                    'attr'=>['placeholder'=>'Data zdarzenia'],
                    'data'=> new \DateTime('+ 70 minutes')
                ])
            ->add(
                'description',
                TextareaType::class,
                [
                    'label'=>false,
                    'attr'=>['placeholder'=>'Opis']
                ])
            ->add(
                'category',
                EntityType::class,
                    array(
                        'class'=>'AppBundle:Category',
                        'query_builder'=>function(EntityRepository $er) use($user){
                        return $er->createQueryBuilder('c')
                            ->where('c.owner=:id')
                            ->orderBy('c.name','ASC')
                            ->setParameter('id',$user);

                    },
                    'choice_label'=>'name',
                    'label'=>'Kategorie'
                    ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['class_name'=>Task::class]);


        $resolver
            ->setRequired('user');
    }

}