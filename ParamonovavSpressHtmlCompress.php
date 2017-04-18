<?php

use Yosymfony\Spress\Core\Plugin\PluginInterface;
use Yosymfony\Spress\Core\Plugin\EventSubscriber;
use Yosymfony\Spress\Core\Plugin\Event\EnvironmentEvent;
use Yosymfony\Spress\Core\Plugin\Event\ContentEvent;
use Yosymfony\Spress\Core\Plugin\Event\FinishEvent;
use Yosymfony\Spress\Core\Plugin\Event\RenderEvent;

class ParamonovavSpressHtmlCompress implements PluginInterface
{
    private $io, $html_compress_exclude = ['.htaccess','robots.txt','crossdomain.xml', 'sitemap.xml', 'rss.xml'];

    private $html_compress_exclude_pattern = '/(.*)?\.(jpe?g|png|gif|ico|svg|psd|tiff|webm|mov|avi|mkv|mp4)$/i';

    public function initialize(EventSubscriber $subscriber)
    {
        $subscriber-> addEventListener('spress.start', 'onStart');
        $subscriber-> addEventListener('spress.after_render_page', 'onAfterRenderPage');
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

    public function onStart(EnvironmentEvent $event)
    {
        $this-> io = $event-> getIO();

        $configValues = $event-> getConfigValues();

        if(isset($configValues['html_compress_exclude']))
        {
            $this->html_compress_exclude = $configValues['html_compress_exclude'];
        }

        if(isset($configValues['html_compress_exclude_pattern']))
        {
            $this->html_compress_exclude_pattern = $configValues['html_compress_exclude_pattern'];
        }

    }
    
    public function onAfterRenderPage(RenderEvent $event)
    {
        $id = $event-> getId();

        if (in_array($id, $this-> html_compress_exclude) || preg_match($this->html_compress_exclude_pattern, $id))
        {
            return;
        }

        $this-> io-> write('Minify/Compress html: '.$event-> getId());

        $event-> setContent( \WyriHaximus\HtmlCompress\Factory::construct()-> compress( $event-> getContent() ) );
    }
}
