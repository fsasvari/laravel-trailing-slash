<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store;
use LaravelTrailingSlash\UrlGenerator;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;

class RoutingRedirectorTest extends TestCase
{
    protected $headers;

    protected $request;

    protected $url;

    protected $session;

    protected $redirect;

    protected function setUp(): void
    {
        $this->headers = m::mock(HeaderBag::class);

        $this->request = m::mock(Request::class);
        $this->request->shouldReceive('isMethod')->andReturn(true)->byDefault();
        $this->request->shouldReceive('method')->andReturn('GET')->byDefault();
        $this->request->shouldReceive('route')->andReturn(true)->byDefault();
        $this->request->shouldReceive('ajax')->andReturn(false)->byDefault();
        $this->request->shouldReceive('expectsJson')->andReturn(false)->byDefault();
        $this->request->headers = $this->headers;

        $this->url = m::mock(UrlGenerator::class);
        $this->url->shouldReceive('getRequest')->andReturn($this->request);
        $this->url->shouldReceive('to')->with('bar', [], null)->andReturn('http://foo.com/bar/');
        $this->url->shouldReceive('to')->with('bar', [], true)->andReturn('https://foo.com/bar/');
        $this->url->shouldReceive('to')->with('login', [], null)->andReturn('http://foo.com/login/');
        $this->url->shouldReceive('to')->with('http://foo.com/bar/', [], null)->andReturn('http://foo.com/bar/');
        $this->url->shouldReceive('to')->with('/', [], null)->andReturn('http://foo.com/');
        $this->url->shouldReceive('to')->with('http://foo.com/bar/#foo', [], null)->andReturn('http://foo.com/bar/#foo');
        $this->url->shouldReceive('to')->with('http://foo.com/bar/?signature=secret', [], null)->andReturn('http://foo.com/bar/?signature=secret');

        $this->session = m::mock(Store::class);

        $this->redirect = new Redirector($this->url);
        $this->redirect->setSession($this->session);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function test_basic_redirect_to()
    {
        $response = $this->redirect->to('bar');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($this->session, $response->getSession());
    }

    public function test_complex_redirect_to()
    {
        $response = $this->redirect->to('bar', 303, ['X-RateLimit-Limit' => 60, 'X-RateLimit-Remaining' => 59], true);

        $this->assertSame('https://foo.com/bar/', $response->getTargetUrl());
        $this->assertEquals(303, $response->getStatusCode());
        $this->assertEquals(60, $response->headers->get('X-RateLimit-Limit'));
        $this->assertEquals(59, $response->headers->get('X-RateLimit-Remaining'));
    }

    public function test_guest_put_current_url_in_session()
    {
        $this->url->shouldReceive('full')->andReturn('http://foo.com/bar/');
        $this->session->shouldReceive('put')->once()->with('url.intended', 'http://foo.com/bar/');

        $response = $this->redirect->guest('login');

        $this->assertSame('http://foo.com/login/', $response->getTargetUrl());
    }

    public function test_guest_put_previous_url_in_session()
    {
        $this->request->shouldReceive('isMethod')->once()->with('GET')->andReturn(false);
        $this->session->shouldReceive('put')->once()->with('url.intended', 'http://foo.com/bar/');
        $this->url->shouldReceive('previous')->once()->andReturn('http://foo.com/bar/');

        $response = $this->redirect->guest('login');

        $this->assertSame('http://foo.com/login/', $response->getTargetUrl());
    }

    public function test_intended_redirect_to_intended_url_in_session()
    {
        $this->session->shouldReceive('pull')->with('url.intended', '/')->andReturn('http://foo.com/bar/');

        $response = $this->redirect->intended();

        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_intended_without_intended_url_in_session()
    {
        $this->session->shouldReceive('forget')->with('url.intended');

        // without fallback url
        $this->session->shouldReceive('pull')->with('url.intended', '/')->andReturn('/');
        $response = $this->redirect->intended();
        $this->assertSame('http://foo.com/', $response->getTargetUrl());

        // with a fallback url
        $this->session->shouldReceive('pull')->with('url.intended', 'bar')->andReturn('bar');
        $response = $this->redirect->intended('bar');
        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_refresh_redirect_to_current_url()
    {
        $this->request->shouldReceive('path')->andReturn('http://foo.com/bar/');
        $response = $this->redirect->refresh();
        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_back_redirect_to_http_referer()
    {
        $this->headers->shouldReceive('has')->with('referer')->andReturn(true);
        $this->url->shouldReceive('previous')->andReturn('http://foo.com/bar/');
        $response = $this->redirect->back();
        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_away_doesnt_validate_the_url()
    {
        $response = $this->redirect->away('bar');
        $this->assertSame('bar', $response->getTargetUrl());
    }

    public function test_secure_redirect_to_https_url()
    {
        $response = $this->redirect->secure('bar');
        $this->assertSame('https://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_action()
    {
        $this->url->shouldReceive('action')->with('bar@index', [])->andReturn('http://foo.com/bar/');
        $response = $this->redirect->action('bar@index');
        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_route()
    {
        $this->url->shouldReceive('route')->with('home')->andReturn('http://foo.com/bar/');
        $this->url->shouldReceive('route')->with('home', [])->andReturn('http://foo.com/bar/');

        $response = $this->redirect->route('home');
        $this->assertSame('http://foo.com/bar/', $response->getTargetUrl());
    }

    public function test_signed_route()
    {
        $this->url->shouldReceive('signedRoute')->with('home', [], null)->andReturn('http://foo.com/bar/?signature=secret');

        $response = $this->redirect->signedRoute('home');
        $this->assertSame('http://foo.com/bar/?signature=secret', $response->getTargetUrl());
    }

    public function test_temporary_signed_route()
    {
        $this->url->shouldReceive('temporarySignedRoute')->with('home', 10, [])->andReturn('http://foo.com/bar/?signature=secret');

        $response = $this->redirect->temporarySignedRoute('home', 10);
        $this->assertSame('http://foo.com/bar/?signature=secret', $response->getTargetUrl());
    }

    public function test_it_sets_and_gets_valid_intended_url()
    {
        $this->session->shouldReceive('put')->once()->with('url.intended', 'http://foo.com/bar/');
        $this->session->shouldReceive('get')->andReturn('http://foo.com/bar/');

        $result = $this->redirect->setIntendedUrl('http://foo.com/bar/');
        $this->assertInstanceOf(Redirector::class, $result);

        $this->assertSame('http://foo.com/bar/', $this->redirect->getIntendedUrl());
    }
}
