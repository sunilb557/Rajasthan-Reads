<?php
class Book extends Model {
    protected $table = 'books';

    public function __construct() {
        parent::__construct();
    }

    public function getPublished($limit = ITEMS_PER_PAGE, $offset = 0) {
        $sql = "
            SELECT b.*, a.name as author_name, a.slug as author_slug, c.name as category_name, c.slug as category_slug, d.name as district_name
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            LEFT JOIN categories c ON b.category_id = c.id
            LEFT JOIN districts d ON b.district_id = d.id
            WHERE b.status = 'published'
            ORDER BY b.featured DESC, b.created_at DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countPublished() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM books WHERE status = 'published'");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? (int)$row['count'] : 0;
    }

    public function findPublishedBySlug($slug) {
        $sql = "
            SELECT b.*, a.name as author_name, a.slug as author_slug, c.name as category_name, c.slug as category_slug
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.slug = ? AND b.status = 'published'
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getFeatured($limit = 4) {
        $sql = "
            SELECT b.*, a.name as author_name, a.slug as author_slug, c.name as category_name, c.slug as category_slug
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.status = 'published' AND b.featured = 1
            ORDER BY b.created_at DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
