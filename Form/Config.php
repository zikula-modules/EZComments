<?php
namespace Zikula\EZCommentsModule\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Zikula\Common\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Config extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BlockType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('allowanon', CheckboxType::class, ['label' => $this->translator->__('Allow non-users to post comments'), 'required' => false])
        ->add('save', SubmitType::class, [
        'label' => $this->translator->__('Save'),
        'icon' => 'fa-check',
        'attr' => [
            'class' => 'btn btn-success'
        ]
    ])
        ->add('cancel', SubmitType::class, [
            'label' => $this->translator->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default'
            ]
        ]);

    }

    public function getName()
    {
        return 'zikulaezcommentsmodule_config';
    }
}
