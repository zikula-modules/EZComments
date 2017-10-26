<?php

namespace Paustian\BookModule\HookSubscriber;

use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\HookSubscriberInterface;
use Zikula\Common\Translator\TranslatorInterface;

class ArticleUiHookSubscriber implements HookSubscriberInterface
{
    const ARTICLE_DISPLAY = 'book.ui_hooks.article.display_view';
    const ARTICLE_PROCESS = 'book.ui_hooks.article.process_edit';
    const ARTICLE_DELETE_PROCESS = 'book.ui_hooks.article.process_delete';

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getOwner()
    {
        return 'PaustianBookModule';
    }

    public function getCategory()
    {
        return UiHooksCategory::NAME;
    }

    public function getTitle()
    {
        return $this->translator->__('Article attachment hooks');
    }

    public function getEvents()
    {
        return [
            UiHooksCategory::TYPE_DISPLAY_VIEW => self::ARTICLE_DISPLAY,
            UiHooksCategory::TYPE_PROCESS_EDIT => self::ARTICLE_PROCESS,
            UiHooksCategory::TYPE_PROCESS_DELETE => self::ARTICLE_DELETE_PROCESS,
        ];
    }
}