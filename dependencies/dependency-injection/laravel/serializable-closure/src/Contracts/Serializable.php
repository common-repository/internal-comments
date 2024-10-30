<?php

namespace DWS_IC_Deps\Laravel\SerializableClosure\Contracts;

interface Serializable
{
    /**
     * Resolve the closure with the given arguments.
     *
     * @return mixed
     */
    public function __invoke();
    /**
     * Gets the closure that got serialized/unserialized.
     *
     * @return \Closure
     */
    public function getClosure();
}
