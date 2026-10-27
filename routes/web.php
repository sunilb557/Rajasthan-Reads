<?php
$router->get('/sitemap.xml', 'SitemapController@index');

$router->get('/search', 'SearchController@index');
$router->get('/search/autocomplete', 'SearchController@autocomplete');

$router->get('/', 'HomeController@index');

// Static Pages
$router->get('/about-us', 'PageController@about');
$router->get('/contact-us', 'PageController@contact');
$router->get('/privacy-policy', 'PageController@privacy');
$router->get('/terms-and-conditions', 'PageController@terms');
$router->get('/disclaimer', 'PageController@disclaimer');

$router->get('/books', 'BookController@index');
$router->get('/books/{slug}', 'BookController@show');
$router->get('/reader/{slug}', 'BookController@reader');

$router->post('/api/reader/{slug}/progress', 'ReaderApiController@progress');
$router->post('/api/reader/{slug}/bookmark', 'ReaderApiController@bookmark');

$router->get('/authors', 'AuthorController@index');
$router->get('/authors/{slug}', 'AuthorController@show');

$router->get('/categories', 'CategoryController@index');
$router->get('/categories/{slug}', 'CategoryController@show');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
//$router->get('/register', 'AuthController@showRegister');
//$router->post('/register', 'AuthController@register');
$router->post('/logout', 'AuthController@logout');

$router->get('/admin', 'AdminDashboardController@index');
$router->get('/admin/stats', 'AdminDashboardController@stats');

$router->get('/admin/books', 'AdminBookController@index');
$router->get('/admin/books/create', 'AdminBookController@create');
$router->post('/admin/books', 'AdminBookController@store');
$router->get('/admin/books/{id}/edit', 'AdminBookController@edit');
$router->post('/admin/books/{id}', 'AdminBookController@update');
$router->post('/admin/books/{id}/delete', 'AdminBookController@delete');

// Admin Authors
$router->get('/admin/authors', 'AdminAuthorController@index');
$router->get('/admin/authors/create', 'AdminAuthorController@create');
$router->post('/admin/authors', 'AdminAuthorController@store');
$router->get('/admin/authors/{id}/edit', 'AdminAuthorController@edit');
$router->post('/admin/authors/{id}', 'AdminAuthorController@update');
$router->post('/admin/authors/{id}/delete', 'AdminAuthorController@delete');

// Admin Categories
$router->get('/admin/categories', 'AdminCategoryController@index');
$router->get('/admin/categories/create', 'AdminCategoryController@create');
$router->post('/admin/categories', 'AdminCategoryController@store');
$router->get('/admin/categories/{id}/edit', 'AdminCategoryController@edit');
$router->post('/admin/categories/{id}', 'AdminCategoryController@update');
$router->post('/admin/categories/{id}/delete', 'AdminCategoryController@delete');

// Bookmarks
$router->get('/bookmarks', 'BookmarkController@index');
$router->post('/api/books/{slug}/bookmarks', 'BookmarkController@store');
$router->get('/api/books/{slug}/bookmarks', 'BookmarkController@getForBook');
$router->post('/bookmarks/{id}/delete', 'BookmarkController@delete');

// Collections
$router->get('/collections', 'CollectionController@index');
$router->get('/collections/{slug}', 'CollectionController@show');
$router->get('/admin/collections', 'CollectionController@adminIndex');
$router->get('/admin/collections/create', 'CollectionController@adminCreate');
$router->post('/admin/collections', 'CollectionController@adminStore');
$router->post('/admin/collections/{id}/books', 'CollectionController@adminAddBook');
$router->post('/admin/collections/{id}/books/{bookId}/remove', 'CollectionController@adminRemoveBook');

// Comments
$router->post('/api/books/{slug}/comments', 'CommentController@store');
$router->get('/api/books/{slug}/comments', 'CommentController@getForBook');
$router->get('/admin/comments/pending', 'CommentController@adminPending');
$router->post('/admin/comments/{id}/approve', 'CommentController@adminApprove');
$router->post('/admin/comments/{id}/reject', 'CommentController@adminReject');

// OCR Search
$router->get('/search/ocr', 'SearchController@ocrSearch');

// Admin Contact Message Routes
$router->get('/admin/contact', 'AdminContactController@index');
$router->get('/admin/contact/{id}', 'AdminContactController@view');
$router->post('/admin/contact/{id}/status', 'AdminContactController@updateStatus');
$router->post('/admin/contact/{id}/delete', 'AdminContactController@delete');

// Admin Settings Routes
$router->get('/admin/settings', 'AdminSettingsController@index');
$router->post('/admin/settings/update', 'AdminSettingsController@update');
$router->post('/contact-us', 'PageController@contact');
