<?php
class SitemapController extends Controller {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        header('Content-Type: application/xml; charset=utf-8');

        $books = $this->db->query("SELECT slug, updated_at FROM books WHERE status = 'published' ORDER BY updated_at DESC")->fetchAll();
        $authors = $this->db->query("SELECT slug, updated_at FROM authors ORDER BY updated_at DESC")->fetchAll();
        $categories = $this->db->query("SELECT slug, updated_at FROM categories ORDER BY updated_at DESC")->fetchAll();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= $this->url(BASE_URL . '/', date('Y-m-d'), 'daily', '1.0');

        // Static Pages
        $xml .= $this->url(BASE_URL . '/about-us', date('Y-m-d'), 'monthly', '0.5');
        $xml .= $this->url(BASE_URL . '/contact-us', date('Y-m-d'), 'monthly', '0.5');
        $xml .= $this->url(BASE_URL . '/privacy-policy', date('Y-m-d'), 'monthly', '0.3');
        $xml .= $this->url(BASE_URL . '/terms-and-conditions', date('Y-m-d'), 'monthly', '0.3');
        $xml .= $this->url(BASE_URL . '/disclaimer', date('Y-m-d'), 'monthly', '0.3');

        // Books list
        $xml .= $this->url(BASE_URL . '/books', date('Y-m-d'), 'daily', '0.9');

        // Individual books
        foreach ($books as $book) {
            $xml .= $this->url(BASE_URL . '/books/' . $book['slug'], $book['updated_at'], 'weekly', '0.8');
        }

        // Authors
        $xml .= $this->url(BASE_URL . '/authors', date('Y-m-d'), 'weekly', '0.7');
        foreach ($authors as $author) {
            $xml .= $this->url(BASE_URL . '/authors/' . $author['slug'], $author['updated_at'], 'monthly', '0.6');
        }

        // Categories
        $xml .= $this->url(BASE_URL . '/categories', date('Y-m-d'), 'weekly', '0.7');
        foreach ($categories as $category) {
            $xml .= $this->url(BASE_URL . '/categories/' . $category['slug'], $category['updated_at'], 'monthly', '0.6');
        }

        $xml .= '</urlset>';

        echo $xml;
        exit;
    }

    private function url($loc, $lastmod, $changefreq, $priority) {
        return "  <url>\n" .
               "    <loc>" . htmlspecialchars($loc) . "</loc>\n" .
               "    <lastmod>" . date('Y-m-d', strtotime($lastmod)) . "</lastmod>\n" .
               "    <changefreq>" . $changefreq . "</changefreq>\n" .
               "    <priority>" . $priority . "</priority>\n" .
               "  </url>\n";
    }
}
?>
