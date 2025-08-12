<?php

namespace Tests\Helpers;

trait CsrfTestHelpers
{
    /**
     * Visit a URI (GET) to seed session & return current CSRF token.
     */
    protected function csrfTokenFromGet(string $uri): string
    {
        $this->get($uri);
        return session()->token();
    }

    /**
     * POST with automatic CSRF token seeding (GET first if $seedUri provided or defaults to $uri if GET safe).
     */
    protected function postWithCsrf(string $uri, array $data = [], ?string $seedUri = null, array $headers = [])
    {
        $seed = $seedUri ?? $uri;
        // Only GET seed if route is not the same actual POST endpoint (avoid MethodNotAllowed) by naive heuristic
        if ($seedUri !== null) {
            $this->get($seed);
        }
        $token = session()->token();
        $payload = array_merge(['_token' => $token], $data);
        $headers = array_merge(['X-CSRF-TOKEN' => $token], $headers);
        return $this->post($uri, $payload, $headers);
    }

    protected function putWithCsrf(string $seedGetUri, string $uri, array $data = [], array $headers = [])
    {
        $this->get($seedGetUri);
        $token = session()->token();
        $payload = array_merge(['_token' => $token], $data);
        $headers = array_merge(['X-CSRF-TOKEN' => $token], $headers);
        return $this->put($uri, $payload, $headers);
    }

    protected function patchWithCsrf(string $seedGetUri, string $uri, array $data = [], array $headers = [])
    {
        $this->get($seedGetUri);
        $token = session()->token();
        $payload = array_merge(['_token' => $token], $data);
        $headers = array_merge(['X-CSRF-TOKEN' => $token], $headers);
        return $this->patch($uri, $payload, $headers);
    }

    protected function deleteWithCsrf(string $seedGetUri, string $uri, array $data = [], array $headers = [])
    {
        $this->get($seedGetUri);
        $token = session()->token();
        $payload = array_merge(['_token' => $token], $data);
        $headers = array_merge(['X-CSRF-TOKEN' => $token], $headers);
        return $this->delete($uri, $payload, $headers);
    }
}
