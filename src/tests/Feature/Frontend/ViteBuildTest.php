<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class ViteBuildTest extends TestCase
{
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_vite_css_is_loaded(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/build/assets/app-', false);
    }

    public function test_vite_js_is_loaded(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/build/assets/app-', false);
    }

    public function test_build_assets_exist(): void
    {
        $manifestPath = public_path('build/manifest.json');
        $this->assertFileExists($manifestPath);

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $this->assertNotEmpty($manifest);
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Strict-Transport-Security');
        $response->assertHeader('X-Content-Type-Options');
        $response->assertHeader('Content-Security-Policy');
    }

    public function test_csp_allows_localhost_in_development(): void
    {
        $response = $this->get('/');
        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("'self'", $csp);
        $this->assertStringContainsString('localhost', $csp);
    }
}
