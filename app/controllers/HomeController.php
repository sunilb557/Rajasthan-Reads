<?php
class HomeController extends Controller {
    public function index() {
        $bookModel = $this->loadModel('Book');
        $featuredBooks = $bookModel->getFeatured(4);
        $recentBooks = $bookModel->getPublished(8, 0);

        $this->render('home/index', [
            'title' => SITE_NAME . ' - Home of Rajasthani Literature',
            'featuredBooks' => $featuredBooks,
            'recentBooks' => $recentBooks,
        ]);
    }
}
?>
