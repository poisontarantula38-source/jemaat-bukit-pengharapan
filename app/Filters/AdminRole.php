<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminRole implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('adminRole') !== 'superadmin') {
            return service('response')
                ->setStatusCode(403)
                ->setBody('Halaman ini hanya dapat diakses oleh admin utama.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
