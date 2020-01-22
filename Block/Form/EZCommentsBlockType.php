<?php

namespace Zikula\EZCommentsModule\Block\Form;

use Symfony\Component\Form\AbstractType;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Common\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EZCommentsBlockType extends AbstractType
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
        $this->addNumDaysToDisplay($builder, $options);
        $this->addShowDateField($builder, $options);
        $this->addShowUsernameField($builder, $options);
        $this->addLinkUsernameField($builder, $options);
    }

    public function addNumCommentsToDisplay(FormBuilderInterface $builder, array $options = [])
    {
        $helpText = $this->__('The number of comments to display.', 'zikulaezcommentsmodule')
            . ' ' . $this->__('Only digits are allowed.', 'zikulaezcommentsmodule')
        ;
        $builder->add('numcomments', IntegerType::class, [
            'label' => $this->__('Number Comments', 'zikulaezcommentsmodule') . ':',
            'attr' => [
                'maxlength' => 2,
                'title' => $helpText
            ],
            'help' => $helpText,
            'empty_data' => 5
        ]);
    }

    public function addNumDaysToDisplay(FormBuilderInterface $builder, array $options = [])
    {
        $helpText = $this->__('The number of days from today to display.', 'zikulaezcommentsmodule')
            . ' ' . $this->__('Only digits are allowed.', 'zikulaezcommentsmodule')
        ;
        $builder->add('numdays', IntegerType::class, [
            'label' => $this->__('Number of Days', 'zikulaezcommentsmodule') . ':',
            'attr' => [
                'maxlength' => 2,
                'title' => $helpText
            ],
            'help' => $helpText,
            'empty_data' => 14
        ]);
    }

    public function addShowDateField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('showdate', ChoiceType::class, [
            'label' => $this->__('Show Date', 'zikulaezcommentsmodule') . ':',
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

    public function addShowUsernameField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('showuser', ChoiceType::class, [
            'label' => $this->__('Show Username', 'zikulaezcommentsmodule') . ':',
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


    public function addLinkUsernameField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('linkuser', ChoiceType::class, [
            'label' => $this->__('Show Date', 'zikulaezcommentsmodule') . ':',
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
        return 'zikulaezcommentsmodule_commentblock';
    }
}