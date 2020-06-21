<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\Block\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class EZCommentsBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numcomments', IntegerType::class, [
                'label' => 'Number Comments',
                'attr' => [
                    'maxlength' => 2,
                    'title' => 'The number of comments to display. Only digits are allowed'
                ],
                'help' => 'The number of comments to display. Only digits are allowed',
                'empty_data' => 5
            ])
            ->add('numdays', IntegerType::class, [
                'label' => 'Number of Days',
                'attr' => [
                    'maxlength' => 2,
                    'title' => 'The number of days from today to display. Only digits are allowed.'
                ],
                'help' => 'The number of days from today to display. Only digits are allowed.',
                'empty_data' => 14
            ])
            ->add('showdate', ChoiceType::class, [
                'label' => 'Show Date',
                'label_attr' => ['class' => 'radio-inline'],
                'empty_data' => 'default',
                'choices' => [
                    'Yes' => 'yes',
                    'No' => 'no',
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('showuser', ChoiceType::class, [
                'label' => 'Show Username',
                'label_attr' => ['class' => 'radio-inline'],
                'empty_data' => 'default',
                'choices' => [
                    'Yes' => 'yes',
                    'No' => 'no',
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('linkuser', ChoiceType::class, [
                'label' => 'Show Date',
                'label_attr' => ['class' => 'radio-inline'],
                'empty_data' => 'default',
                'choices' => [
                    'Yes' => 'yes',
                    'No' => 'no',
                ],
                'multiple' => false,
                'expanded' => true
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'zikulaezcommentsmodule_commentblock';
    }
}
