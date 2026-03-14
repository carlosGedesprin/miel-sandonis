<?php
namespace src\util\utils;

/**
 * Trait blog
 * @package Utils
 */
trait blog
{
    /**
     * Get the blog category title
     *
     * @param string $blog_category_id  Id of the category searched
     * @return string Title of the blog category searched
     * @throws void
     */
    public function getBlogCategoryTitle( $blog_category_id )
    {
        return $this->db->fetchField('blog_category', 'title', ['id' => $blog_category_id]);
    }
    /**
     * Get the blog article title
     *
     * @param string $blog_article_id  Id of the article searched
     * @return string Title of the blog article searched
     * @throws void
     */
    public function getBlogArticleTitle( $blog_article_id )
    {
        return $this->db->fetchField('blog_article', 'title', ['id' => $blog_article_id]);
    }
}
