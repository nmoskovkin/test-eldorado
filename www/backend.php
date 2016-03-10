<?php
use Backend\Api\ValidationException;
use Backend\Expression\Calculator;
use Backend\Expression\Parser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/expression/evaluate', function(Request $request)
{
    if (!$request->get('jsonp_callback')) {
        throw new NotFoundHttpException;
    }

    try {
        $expression = $request->get('expression');

        if (!$expression) {
            throw new ValidationException('A blank expression.');
        }

        $parser = new Parser();
        $parsingResult = $parser->parse($expression);

        $calculator = new Calculator();
        $evaluatingResult = $calculator->calculate($parsingResult);

        $result = array(
            'status' => true,
            'result' => $evaluatingResult
        );
    } catch (\Exception $e) {
        switch (true) {
            case $e instanceof \Backend\Api\ValidationException:
            case $e instanceof \Backend\Expression\EvaluateException:
                $error = $e->getMessage();
                break;
            case $e instanceof \Backend\Expression\ParseException:
                $error = 'Wrong expression.';
                break;
            default:
                $error = 'Internal error.';
        }

        $result = array(
            'status' => false,
            'error' => $error
        );
    }

    $response = new Response();
    $response->headers->set('Content-Type', 'application/javascript');
    $response->setContent($request->get('jsonp_callback') . '(' . json_encode($result). ');');

    return $response;
});

$app->run();
