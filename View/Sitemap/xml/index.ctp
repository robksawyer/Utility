<?php
// Render without XmlEngine as we need the namespace in urlset
// Also use echo because <? short tags will explode if enabled

echo '<?xml version="1.0" encoding="UTF-8"?>';
if (!$imageheader){
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
} else {
	echo '<urlset xmlns="http://www.google.com/schemas/sitemap-images/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
}

if ($sitemap) {
    foreach ($sitemap as $item) {
        echo '<url>';

        foreach ($item as $key => $value) {

        	if( $key != 'image' ){

        		echo sprintf('<%s>%s</%s>', $key, $value, $key);

        	} else {

        		echo sprintf('<%s><image:loc>%s</image:loc></%s>', $key, $value, $key);
        	}
            
        }

        echo '</url>';
    }
}

echo '</urlset>';