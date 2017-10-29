<?php

namespace pinfirestudios\craft3bugsnag; 

class ErrorHandler extends \craft\web\ErrorHandler
{
    use \pinfirestudios\yii2bugsnag\BugsnagErrorHandlerTrait;
}
