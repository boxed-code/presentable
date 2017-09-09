<?php

namespace BoxedCode\Presentable;

use ReflectionClass;

trait Presentable
{
    /**
     * Model presenter class namespace.
     * 
     * @var string
     *
     * protected $presenterNamespace;
     */

    /**
     * Class name of this models presenter.
     * 
     * @var string
     * protected $presenter = \Marque\Presenters\Presenter::class;
     */

    /**
     * Presentable methods to compute and return in array 
     * representations of the model.
     * 
     * @var array
     *
     * protected $presentable = [];
     */
    
    /**
     * Cached presenter instance.
     * 
     * @var Cached presenter instance.
     */
    protected $presenterInstance;

    /**
     * Set a the models presenter instance.
     * 
     * @param Presenter $presenter
     */
    public function setPresenter(Presenter $presenter)
    {
        $presenter->setModel($this);

        $this->presenter = $presenter;

        return $this;
    }

    /**
     * Get the presenter namespace.
     * 
     * @return string
     */
    public function getPresenterNamespace()
    {
        if (! empty($this->presenterNamespace)) {
            return $this->presenterNamespace;
        }

        $namespace = explode('\\', get_class($this));

        return $namespace[0].'\\Presenters';
    }

    /**
     * Get the models presenter.
     * 
     * @return \Marque\Support\Eloquent\Presenters\Presenter
     */
    public function present()
    {
        if (! $this->presenterInstance && ! $presenter = $this->presenter) {
            // Guess the presenter class name.
            $reflect = new ReflectionClass($this);
            $class_name = $reflect->getShortName().'Presenter';
            $presenter = implode('\\', [$this->getPresenterNamespace(), $class_name]);
        }

        if (! $this->presenterInstance) {
            $this->presenterInstance = app()->make($presenter);

            $this->presenterInstance->setModel($this);
        }

        return $this->presenterInstance;
    }

    /**
     * Get the array representation of the model.
     * 
     * @return array
     */
    public function attributesToArray()
    {
        $data = parent::attributesToArray();

        if (isset($this->presentable)) {
            foreach ($this->presentable as $presentable) {
                $data[$presentable] = $this->present()->$presentable;
            }
        }

        return $data;
    }

    public function __call($name, $args)
    {
        $presenter = $this->present();

        if (method_exists($presenter, $name)) {
            return $presenter->$name($args);
        }

        return parent::__call($name, $args);
    }
}
