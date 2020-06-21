<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\Block\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class MostCommentsBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('numcommenters', IntegerType::class, [
                'label' => 'Number Comments',
                'attr' => [
                    'maxlength' => 2,
                    'title' => 'The number of commenters to display. Only digits are allowed'
                ],
                'help' => 'The number of commenters to display. Only digits are allowed',
                'empty_data' => 5
            ])
            ->add('showcount', ChoiceType::class, [
                'label' => 'Show Count',
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
        return 'zikulaezcommentsmodule_mostcommentsblock';
    }
}
