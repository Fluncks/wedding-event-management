<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['form', 'url', 'html', 'location'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * Render a view with common auth UI data.
     *
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): string
    {
        $session = session();
        $data['currentUser'] = [
            'name'  => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role'  => $session->get('user_role'),
        ];
        $data['isAdmin'] = $session->get('user_role') === 'admin';

        return view($view, $data);
    }
}
