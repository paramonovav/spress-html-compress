<?php

use Yosymfony\Spress\Core\Plugin\PluginInterface;
use Yosymfony\Spress\Core\Plugin\EventSubscriber;
use Yosymfony\Spress\Core\Plugin\Event\EnvironmentEvent;
use Yosymfony\Spress\Core\Plugin\Event\RenderEvent;
use Yosymfony\Spress\Core\IO\IOInterface;

class ParamonovavSpressHtmlCompress implements PluginInterface
{
    /** @var IOInterface */
    private $io;


    private $html_compress_exclude_pattern = '/(.*)?\.(jpe?g|png|gif|ico|svg|psd|tiff|webm|mov|avi|mkv|mp4)$/i';

    /** @var string[] */
    private $html_compress_exclude = ['.htaccess','robots.txt','crossdomain.xml', 'sitemap.xml', 'rss.xml'];

    /** @var bool */
    private $htmlCompress  = true;

    /**
     * @param EventSubscriber $subscriber
     */

    public function initialize(EventSubscriber $subscriber)
    {
        $subscriber->addEventListener('spress.start', 'onStart');
        $subscriber->addEventListener('spress.after_render_page', 'onAfterRenderPage');
    }

    /**
     * @return string[]
     */
    public function getMetas()
    {
        return [
            'name' => 'paramonovav/spress-html-compress',
            'description' => 'Compress/minify your HTML',
            'author' => 'paramonovav',
            'license' => 'MIT',
        ];
    }

    /**
     * @param EnvironmentEvent $event
     */
    public function onStart(EnvironmentEvent $event)
    {
        $this->io = $event->getIO();

        $configValues = $event->getConfigValues();

        $this->htmlCompress = !empty($configValues['html_compress']);

        if(isset($configValues['html_compress_exclude']))
        {
            $this->html_compress_exclude = $configValues['html_compress_exclude'];
        }

        if(isset($configValues['html_compress_exclude_pattern']))
        {
            $this->html_compress_exclude_pattern = $configValues['html_compress_exclude_pattern'];
        }

    }

    /**
     * @param RenderEvent $event
     */
    public function onAfterRenderPage(RenderEvent $event)
    {
        $id = $event->getId();

        if (!$this->htmlCompress || in_array($id, $this-> html_compress_exclude) || preg_match($this->html_compress_exclude_pattern, $id))
        {
            return;
        }

        $this->io->write('Minify/Compress html: '.$event->getId());

        $event->setContent( \WyriHaximus\HtmlCompress\Factory::construct()->compress( $event->getContent() ) );
    }
}
