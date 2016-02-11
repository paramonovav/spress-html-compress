<?php

use Yosymfony\Spress\Core\Plugin\PluginInterface;
use Yosymfony\Spress\Core\Plugin\EventSubscriber;
use Yosymfony\Spress\Core\Plugin\Event\EnvironmentEvent;
use Yosymfony\Spress\Core\Plugin\Event\ContentEvent;
use Yosymfony\Spress\Core\Plugin\Event\FinishEvent;
use Yosymfony\Spress\Core\Plugin\Event\RenderEvent;

class ParamonovavSpressHtmlCompress implements PluginInterface
{
    private $io;

    public function initialize(EventSubscriber $subscriber)
    {
        $subscriber->addEventListener('spress.after_render_page', 'onAfterRenderPage');
    }

    public function getMetas()
    {
        return [
            'name' => 'paramonovav/spress-html-compress',
            'description' => 'Compress/minify your HTML',
            'author' => 'paramonovav',
            'license' => 'MIT',
        ];
    }
    
    public function onAfterRenderPage(RenderEvent $event)
    {
        $content = $event->getContent();

        $parser = \WyriHaximus\HtmlCompress\Factory::construct();
        $compressedHtml = $parser->compress($content);        

        $event->setContent($compressedHtml);
    }

    public function onFinish(FinishEvent $event)
    {

    }
}