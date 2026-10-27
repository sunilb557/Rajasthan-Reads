<?php
require_once 'config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

try {
    // 1. Add Categories
    $categories = [
        ['History', 'history', 'Historical records and narratives of Rajasthan'],
        ['Literature', 'literature', 'Classical and modern Rajasthani literature'],
        ['Folklore', 'folklore', 'Traditional folk tales and legends'],
        ['Poetry', 'poetry', 'Rajasthani poetic works and anthologies'],
        ['Heritage', 'heritage', 'Cultural heritage and architectural documentation']
    ];

    foreach ($categories as $cat) {
        $stmt = $db->prepare("INSERT IGNORE INTO categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->execute($cat);
    }
    echo "Categories seeded.\n";

    // 2. Add Authors
    $authors = [
        ['Vijaydan Detha', 'vijaydan-detha', 'Renowned Rajasthani writer and folklorist'],
        ['Kavi Shamaldas', 'kavi-shamaldas', 'Author of Vir Vinod, the history of Mewar'],
        ['Colonel James Tod', 'james-tod', 'British political agent and historian of Rajasthan'],
        ['Suryamal Misran', 'suryamal-misran', 'Great Rajasthani poet and historian']
    ];

    foreach ($authors as $author) {
        $stmt = $db->prepare("INSERT IGNORE INTO authors (name, slug, bio) VALUES (?, ?, ?)");
        $stmt->execute($author);
    }
    echo "Authors seeded.\n";

    // 3. Add Books
    $books = [
        [
            'Annals and Antiquities of Rajasthan', 
            'annals-and-antiquities-of-rajasthan', 
            'The definitive history of the Rajput states.', 
            3, 1, 'english', 1829, 800, 'published'
        ],
        [
            'Batan Ri Phulwari', 
            'batan-ri-phulwari', 
            'A massive collection of Rajasthani folk tales.', 
            1, 3, 'rajasthani', 1960, 450, 'published'
        ],
        [
            'Vir Vinod', 
            'vir-vinod', 
            'The comprehensive history of Mewar region.', 
            2, 1, 'hindi', 1886, 1200, 'published'
        ],
        [
            'Vansh Bhaskar', 
            'vansh-bhaskar', 
            'A poetic historical account of Bundi state.', 
            4, 4, 'rajasthani', 1840, 600, 'published'
        ]
    ];

    foreach ($books as $book) {
        $stmt = $db->prepare("INSERT IGNORE INTO books (title, slug, description, author_id, category_id, language, publication_year, pages, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($book);
    }
    echo "Books seeded.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
