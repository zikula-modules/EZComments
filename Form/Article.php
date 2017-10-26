<?php
namespace Paustian\BookModule\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Bundle\FormExtensionBundle\Form\DataTransformer\NullToEmptyTransformer;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\SettingsModule\Api\ApiInterface\LocaleApiInterface;

class Article extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LocaleApiInterface
     */
    private $localeApi;

    /**
     * constructor.
     * @param TranslatorInterface $translator
     * @param LocaleApiInterface $localeApi
     */
    public function __construct(
        TranslatorInterface $translator,
        LocaleApiInterface $localeApi
    ) {
        $this->translator = $translator;
        $this->localeApi = $localeApi;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('contents', TextareaType::class)
            ->add($builder->create('lang', ChoiceType::class, array(
                'choices' => $this->localeApi->getSupportedLocaleNames(null, $options['locale']),
                'required' => false,
                'placeholder' => $this->translator->__('All')
                ))->addModelTransformer(new NullToEmptyTransformer()))
            ->add('next', NumberType::class, ['label' => $this->translator->__('Next'), 'required' => true])
            ->add('prev', NumberType::class, ['label' => $this->translator->__('Previous'), 'required' => true])
            ->add('number', NumberType::class, ['label' => $this->translator->__('Article Order Number'), 'required' => true])
            ->add('save', SubmitType::class, ['label' => $this->translator->__('Edit Article')]);
    }

    public function getBlockPrefix()
    {
        return 'paustianbookmodule_article';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Paustian\BookModule\Entity\BookArticlesEntity',
            'locale' => 'en'
        ]);
    }

}
