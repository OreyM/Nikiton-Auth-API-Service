<?php

namespace App\Actions;

abstract class Action
{
    protected static Action $instance;

    abstract protected function handle();

    public static function call($class): static
    {
        static::$instance = \App::make($class);
        return static::$instance;
    }

    public function withParam(string $name, mixed $value): static
    {
        $this->{$name} = $value;
        return $this;
    }

    public function withParams(array $params): static
    {
        foreach ($params as $name => $value) {
            $this->{$name} = $value;
        }
        return $this;
    }

    public function run(): mixed
    {
        return $this->handle();
    }
}
