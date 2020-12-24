<?php

namespace MooMoo\Platform\Bundle\PostBundle\Url;

use WP_Post;

class PostUrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var array
     */
    private $queryArguments = [];

    /**
     * @inheritDoc
     */
    public function generate()
    {
        /** @var WP_Post $post */
        $post = get_post();
        if ($post) {
            $link = get_permalink($post->ID);
            if (force_ssl_admin()) {
                $link = str_replace("http://", "https://", $link);
            }
            foreach ($this->queryArguments as $key => $value) {
                $link = add_query_arg($key, $value ?: 'true', $link);
            }

            return $link;
        }

        return null;
    }

    /**
     * @param string $key
     * @param string|null $value
     */
    public function addQueryArgument($key, $value = null)
    {
        $this->queryArguments[$key] = $value;
    }
}
