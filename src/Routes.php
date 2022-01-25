<?php

namespace HeadlessLaravel\Notifications;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class Routes
{
    protected $types = [];

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function setTypes(array $types = []): self
    {
        $this->types['only'] = $types;

        return $this;
    }

    public function notifications(): self
    {
        return $this;
    }

    public function make()
    {
        $key = $this->notificationKey();

        return array_map(function ($endpoint) use ($key) {
            return array_merge($endpoint, [
                'action'       => [NotificationController::class, $endpoint['type']],
                'name'         => $key.'.'.$endpoint['type'],
            ]);
        }, $this->endpoints());
    }

    public function create(): array
    {
        $routes = $this->make();
        $middlewares = $this->notificationMiddelwares();

        foreach ($routes as $route) {
            $this->router
                ->addRoute($route['verb'], $route['endpoint'], $route['action'])
                ->middleware($middlewares)
                ->name($route['name']);
        }

        return $routes;
    }

    public function endpoints(): array
    {
        $endpoints = $this->notificationEndpoints();

        if (!$this->types) {
            return $endpoints;
        }

        if (isset($this->types['only']) and count($this->types['only'])) {
            $only = $this->types['only'];
            $endpoints = array_filter($endpoints, function ($endpoint) use ($only) {
                return in_array($endpoint['type'], $only);
            });
        }

        if (isset($this->types['except']) and count($this->types['except'])) {
            $except = $this->types['except'];
            $endpoints = array_filter($endpoints, function ($endpoint) use ($except) {
                return !in_array($endpoint['type'], $except);
            });
        }

        return $endpoints;
    }

    private function notificationEndpoints(): array
    {
        $notificationKey = $this->notificationKey();
        $singularKey = Str::singular($notificationKey);

        return [
            ['type' => 'all', 'verb' => ['GET', 'HEAD'], 'endpoint' => $notificationKey],
            ['type' => 'unread', 'verb' => ['GET', 'HEAD'], 'endpoint' => "$notificationKey/unread"],
            ['type' => 'read', 'verb' => ['GET', 'HEAD'], 'endpoint' => "$notificationKey/read"],
            ['type' => 'count', 'verb' => ['GET', 'HEAD'], 'endpoint' => "$notificationKey/count"],
            ['type' => 'clear', 'verb' => 'POST', 'endpoint' => "$notificationKey/clear"],
            ['type' => 'markAsRead', 'verb' => 'POST', 'endpoint' => "$notificationKey/{{$singularKey}}/mark-as-read"],
            ['type' => 'destroy', 'verb' => 'DELETE', 'endpoint' => "$notificationKey/{{$singularKey}}"],
        ];
    }

    protected function notificationKey(): string
    {
        return 'notifications';
    }

    protected function notificationMiddelwares()
    {
        return ['web', 'auth', 'verified'];
    }

    /**
     * @param array|string $types
     *
     * @return $this
     */
    public function only($types): self
    {
        if (is_string($types)) {
            $types = [$types];
        }

        $this->types['only'] = $types;

        return $this;
    }

    /**
     * @param array|string $types
     *
     * @return $this
     */
    public function except($types): self
    {
        if (is_string($types)) {
            $types = [$types];
        }

        $this->types['except'] = $types;

        return $this;
    }

    public function __destruct()
    {
        return $this->create();
    }
}
