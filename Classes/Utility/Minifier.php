<?php
namespace WapplerSystems\WsT3bootstrap\Utility;



class Minifier {

    /**
     * @param $params
     * @param bool $fakeThis
     * @return string
     */
	public function compressJS(&$params, $fakeThis = false) {

		$script = $params['script'];

        $minifier = new \MatthiasMullie\Minify\JS();
        $minifier->add($script);

		return $minifier->minify();
	}

    /**
     * @param $params
     * @param bool $fakeThis
     * @return string
     */
    public function compressCSS(&$params, $fakeThis = false) {

        $script = $params['script'];

        $minifier = new \MatthiasMullie\Minify\CSS();
        $minifier->add($script);

        return $minifier->minify();
    }

}
