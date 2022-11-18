<?php

class FrameWordPressValetDriver extends BasicValetDriver
{

    public $wp_root = 'wordpress';
    public $public_dir = '';
    public $debug = false;

    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {   
        if (file_exists($sitePath.'/site/wp-config.php') || file_exists($sitePath.'/site/wp-config-sample.php'))
        {
            $this->public_dir = '/site';
            return true;
        }

        if (file_exists($sitePath.'/dist/wp-config.php') || file_exists($sitePath.'/dist/wp-config-sample.php'))
        {
            $this->public_dir = '/dist';
            return true;
        }

        if (file_exists($sitePath.'/wp-config.php') || file_exists($sitePath.'/wp-config-sample.php'))
        {
            $this->public_dir = '';
            return true;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {

        if ( $this->debug ){
            echo '<pre>';
            echo 'frontControllerPath initial' .PHP_EOL;
            var_dump( $sitePath, $siteName, $uri );
            echo '</pre>' . PHP_EOL;
            echo PHP_EOL . '----------------------------------------------' . PHP_EOL;
        }

        $sitePath = $sitePath . $this->public_dir;

        $multisiteRegex = $re = '/^define\( ?\'MULTISITE\' ?, ?true ?\)/m';
        @$isMulti = preg_match( $multisiteRegex, file_get_contents($sitePath . '/wp-config.php'), $matches );

        $multisite = $isMulti > 0;

        if ( $this->debug ){
            echo '<pre>';
            echo 'frontControllerPath mid' .PHP_EOL;
            var_dump( $sitePath, $siteName, $uri, $multisite );
            echo '</pre>' . PHP_EOL;
            echo PHP_EOL . '----------------------------------------------' . PHP_EOL;
        }

        if ( $multisite )
            $uri = $this->getMultisiteUri($uri, $sitePath);

        $path = parent::frontControllerPath(
            $sitePath, $siteName, $this->forceTrailingSlash($uri)
        );

        if ( $this->debug ){
            echo '<pre>';
            echo 'frontControllerPath final' .PHP_EOL;
            var_dump( $sitePath, $siteName, $uri, $path );
            echo '</pre>' . PHP_EOL;
            echo PHP_EOL . '----------------------------------------------' . PHP_EOL;
        }

        $_SERVER['PHP_SELF'] = str_replace( $sitePath, '', $path );

        if ( $this->debug ){
            die();
        }

        return $path;
    }

    /**
     * Modify the URL to account for multisite directories.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    private function getMultisiteUri( $uri, $sitePath ) {

        if ( $this->debug ){
            echo '<pre>';
            echo 'getMultisiteUri initial' .PHP_EOL;
            var_dump( $uri, $sitePath, $this->public_dir );
            echo '</pre>' . PHP_EOL;
            echo PHP_EOL . '----------------------------------------------' . PHP_EOL;
        }

        $_SERVER['PHP_SELF']    = $uri;
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        // If URI contains one of the main WordPress directories, and it's not a request for the Network    Admin,
        // drop the subdirectory segment before routing the request
        if ( stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false ) {

            if ( stripos($uri, 'wp-admin/network') === false ) {
                $uri = substr($uri, stripos($uri, '/wp-') );
            }

            if ( stripos($uri, 'wp-admin/network') === false && $this->wp_root !== false && file_exists($sitePath . "/{$this->wp_root}/wp-admin") ) {
                $uri = "/{$this->wp_root}" . $uri;
            }

        }

        if ( $this->debug ){
            echo '<pre>';
            echo 'getMultisiteUri mid' .PHP_EOL;
            var_dump( $uri, $sitePath, $this->public_dir );
            echo '</pre>' . PHP_EOL;
            echo PHP_EOL . '----------------------------------------------' . PHP_EOL;
        }
        // Handle wp-cron.php properly
        if ( stripos($uri, 'wp-cron.php') !== false ) {
            $new_uri = substr($uri, stripos($uri, '/wp-') );

            if ( file_exists( $sitePath . $new_uri ) ) {
                $uri = $sitePath . $new_uri;
            }
        }

        if ( $this->debug ){
            echo '<pre>';
            echo 'getMultisiteUri final' .PHP_EOL;
            var_dump( $uri, $sitePath, $this->public_dir );
            echo '</pre>' . PHP_EOL;
            echo PHP_EOL . '----------------------------------------------' . PHP_EOL;
        }


        return $uri;

    }

    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/wp-admin')) == '/wp-admin') {
            header('Location: '.$uri.'/'); die;
        }
        return $uri;
    }

    
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        $sitePath = $sitePath . $this->public_dir;
        // If the URI contains one of the main WordPress directories and it doesn't end with a slash,
        // drop the subdirectory from the URI and check if the file exists. If it does, return the new uri.
        if ( stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false ) {
            if ( substr($uri, -1, 1) == "/" ) return false;

            $new_uri = substr($uri, stripos($uri, '/wp-') );

            if ( $this->wp_root !== false && file_exists($sitePath . "/{$this->wp_root}/wp-admin") ) {
                $new_uri = "/{$this->wp_root}" . $new_uri;
            }

            if ( file_exists( $sitePath . $new_uri ) ) {
                return $sitePath . $new_uri;
            }
        }

        return parent::isStaticFile( $sitePath, $siteName, $uri );
    }

}
