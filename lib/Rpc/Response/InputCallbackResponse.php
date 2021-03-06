<?php

namespace Phpactor\Rpc\Response;

use Phpactor\Rpc\Response;
use Phpactor\Rpc\Request;
use Phpactor\Rpc\Response\Input\Input;

class InputCallbackResponse implements Response
{
    /**
     * @var ActionRequest
     */
    private $callbackAction;

    /**
     * @var array
     */
    private $inputs = [];

    private function __construct(Request $callbackAction, array $inputs)
    {
        $this->callbackAction = $callbackAction;

        foreach ($inputs as $input) {
            $this->add($input);
        }
    }

    public static function fromCallbackAndInputs(Request $callbackAction, array $inputs)
    {
        return new self($callbackAction, $inputs);
    }

    public function name(): string
    {
        return 'input_callback';
    }

    public function inputs(): array
    {
        return $this->inputs;
    }

    public function parameters(): array
    {
        return [
            'inputs' => array_map(function (Input $input) {
                return [
                    'name' => $input->name(),
                    'type' => $input->type(),
                    'parameters' => $input->parameters()
                ];
            }, $this->inputs),
            'callback' => [
                'action' => $this->callbackAction->name(),
                'parameters' => $this->callbackAction->parameters()
            ],
        ];
    }

    public function callbackAction(): Request
    {
        return $this->callbackAction;
    }

    private function add(Input $input)
    {
        $this->inputs[] = $input;
    }
}
