<?php

namespace App\Form;

use App\Entity\Bag;
use App\Entity\Condition;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
             ->add('img', FileType::class, [
                'label' => 'illustration', 
                'mapped'=> false 
            ])
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
            ])
            ->add('bagCondition', EntityType::class, [
                'class' => Condition::class,
                'choice_label' => 'name',
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('borrower', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bag::class,
        ]);
    }
}
