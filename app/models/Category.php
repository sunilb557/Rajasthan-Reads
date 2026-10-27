<?php
class Category extends Model {
    protected $table = 'categories';

    public function __construct() {
        parent::__construct();
    }

    public function getAllWithCounts() {
        $sql = "
            SELECT c.*, COUNT(b.id) as book_count
            FROM categories c
            LEFT JOIN books b ON b.category_id = c.id AND b.status = 'published'
            GROUP BY c.id
            ORDER BY c.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlugWithCounts($slug) {
        $sql = "
            SELECT c.*, COUNT(b.id) as book_count
            FROM categories c
            LEFT JOIN books b ON b.category_id = c.id AND b.status = 'published'
            WHERE c.slug = ?
            GROUP BY c.id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
?>
