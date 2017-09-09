<?php

namespace BoxedCode\Presentable;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractPresenter implements Presenter
{
    /**
     * Model instance.
     * 
     * @var Model
     */
    protected $model;

    /**
     * Set the model instance.
     * 
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Handle dynamic property accessor calls.
     * 
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $studly = studly_case($name);

        $studly_lower = strtolower($studly[0]).substr($studly, 1);

        $names = [$name, $studly, $studly_lower];

        foreach ($names as $name) {
            if (method_exists($this, $name)) {
                return call_user_func([$this, $name]);
            }
        }
    }
}
