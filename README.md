## Minify HTML

![Spress 2 ready](https://img.shields.io/badge/Spress%202-ready-brightgreen.svg)

Compress/minify your HTML

### How to install?

Go to your Spress site and add the following to your `composer.json` and run 
`composer update`:

```json
"require": {
    "paramonovav/spress-html-compress": "2.0.*"
}
```

### How to use?

Add the following to your config.yml to exclude some files from minify/compress process:

```yaml
html_compress_exclude: ['.htaccess','robots.txt','crossdomain.xml', 'sitemap.xml','nortonsw_bc7be3d0-796e-0.html','BingSiteAuth.xml']
html_compress_exclude_pattern: '/(.*)?\.(jpe?g|png|gif|ico|svg|psd|tiff|webm|mov|avi|mkv|mp4|eot|ttf|otf|woff|woff2|webp)$/i'
```

Just run build command:

```bash
spress site:build
```

If you want to disable compression use the following config

```yaml
html_compress: false
```
