<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-select']
            ])
            ->add('title',null,[
            'label_attr' => ['class'=>'form-label'],
            'attr'       => ['class'=>'form-control','popo' => 'manger']
            ])
            ->add('description',null,[
                'label_attr' => ['class'=>'form-label'],
                'attr'       => ['class'=>'form-control']
                ])
            ->add('image',null,[
                'label_attr' => ['class'=>'form-label'],
                'attr'       => ['class'=>'form-control']
                ])
            ->add('content',null,[
                'label_attr' => ['class'=>'form-label'],
                'attr'       => ['class'=>'form-control']
                ])
            ->add('pinned')
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('tags',EntityType::class,[
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-check']
            ])
            // ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
