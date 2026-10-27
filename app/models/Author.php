<?php
class Author extends Model {
    protected $table = 'authors';

    public function __construct() {
        parent::__construct();
    }

    public function getAllWithCounts() {
        $sql = "
            SELECT a.*, COUNT(b.id) as book_count
            FROM authors a
            LEFT JOIN books b ON b.author_id = a.id AND b.status = 'published'
            GROUP BY a.id
            ORDER BY a.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlugWithCounts($slug) {
        $sql = "
            SELECT a.*, COUNT(b.id) as book_count
            FROM authors a
            LEFT JOIN books b ON b.author_id = a.id AND b.status = 'published'
            WHERE a.slug = ?
            GROUP BY a.id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
?>
