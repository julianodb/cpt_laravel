<?php

namespace App\Util;

class MercadoPublico {

    /**
     * Returns the contents of the requested url.
     *
     * @return string
     */
    public function get($url) {
    	return file_get_contents($url);
    }

}