<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function success(string $message, ?string $route = null): RedirectResponse
    {
        $response = redirect()->back()->with('success', $message);

        if ($route) {
            return redirect()->route($route)->with('success', $message);
        }

        return $response;
    }

    protected function error(string $message, ?string $route = null): RedirectResponse
    {
        $response = redirect()->back()->with('error', $message);

        if ($route) {
            return redirect()->route($route)->with('error', $message);
        }

        return $response;
    }

    protected function view(string $view, array $data = []): View
    {
        return view($view, $data);
    }
}
