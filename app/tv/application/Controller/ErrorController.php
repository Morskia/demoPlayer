<?php


namespace Mini\Controller;

class ErrorController
{
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function index()
    {
        // load views
	    require APP . 'view/_templates/front_header.php';
        require APP . 'view/error/index.php';
	    require APP . 'view/_templates/front_footer.php';
    }
}
