<?php

namespace Zikula\EZCommentsModule\Block\Form;

use Symfony\Component\Form\AbstractType;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Common\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MostCommentsBlockType extends AbstractType
{
    use TranslatorTrait;

    public function __construct(
        TranslatorInterface $translator) {
        $this->setTranslator($translator);
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNumCommentsToDisplay($builder, $options);
        $this->addShowCountField($builder, $options);
    }

    public function addNumCommentsToDisplay(FormBuilderInterface $builder, array $options = [])
    {
        $helpText = $this->__('The number of commenters to display.', 'zikulaezcommentsmodule')
            . ' ' . $this->__('Only digits are allowed.', 'zikulaezcommentsmodule')
        ;
        $builder->add('numcommenters', IntegerType::class, [
            'label' => $this->__('Number Comments', 'zikulaezcommentsmodule') . ':',
            'attr' => [
                'maxlength' => 2,
                'title' => $helpText
            ],
            'help' => $helpText,
            'empty_data' => 5
        ]);
    }

    public function addShowCountField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('showcount', ChoiceType::class, [
            'label' => $this->__('Show Count', 'zikulaezcommentsmodule') . ':',
            'label_attr' => ['class' => 'radio-inline'],
            'empty_data' => 'default',
            'choices' => [
                $this->__('Yes', 'zikulaezcommentsmodule') => 'yes',
                $this->__('No', 'zikulaezcommentsmodule') => 'no',
            ],
            'multiple' => false,
            'expanded' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return 'zikulaezcommentsmodule_mostcommentsblock';
    }
}