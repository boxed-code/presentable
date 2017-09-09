<?php

namespace BoxedCode\Presentable;

use Illuminate\Database\Eloquent\Model;

interface Presenter {
    public function setModel(Model $model);
}