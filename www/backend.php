<?php
use Backend\Api\ValidationException;
use Backend\Expression\Calculator;
use Backend\Expression\Parser;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->post('/expression/evaluate', function(Request $request) {
    try {
        $expression = $request->get('expression');

        if (!$expression) {
            throw new ValidationException('A blank expression.');
        }

        $parser = new Parser();
        $parsingResult = $parser->parse($expression);

        $calculator = new Calculator();
        $evaluatingResult = $calculator->calculate($parsingResult);

        return json_encode(array(
            'status' => true,
            'result' => $evaluatingResult
        ));
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

        return json_encode(array(
            'status' => false,
            'error' => $error
        ));
    }
});

$app->run();
